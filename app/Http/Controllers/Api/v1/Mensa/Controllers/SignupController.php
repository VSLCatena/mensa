<?php


namespace App\Http\Controllers\Api\v1\Mensa\Controllers;


use App\Contracts\RemoteUserLookup;
use App\Http\Controllers\Api\v1\Common\Mappers\FoodOptionsMapper;
use App\Http\Controllers\Api\v1\Mensa\Mappers\SignupMapper;
use App\Http\Controllers\Api\v1\Utils\ErrorMessages;
use App\Models\Mensa;
use App\Models\Signup;
use App\Models\User;
use Error;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SignupController extends Controller
{
    use SignupMapper, FoodOptionsMapper;

    private RemoteUserLookup $remoteLookup;

    /**
     * Create a new controller instance.
     *
     * @param RemoteUserLookup $remoteLookup
     */
    public function __construct(RemoteUserLookup $remoteLookup)
    {
        $this->remoteLookup = $remoteLookup;
    }


    public function getSignup(Request $request, string $mensaId): ?JsonResponse
    {
        $user = Auth::guard('sanctum')->user() ?? Auth::getUser();

        if ($request->has('confirmation_code')) {
            $signups = Signup::where('mensa_id', '=', $mensaId)
                ->where('confirmation_code', '=', $request->get('confirmation_code'))
                ->get();
        } else if ($user != null) {
            $signups = Signup::where('mensa_id', '=', $mensaId)
                ->where('user_id', $user->id)
                ->get();
        } else {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return response()->json($signups->map(function ($signup) use ($user) {
            return $this->mapSignup($signup, $user);
        }));
    }

    /**
     * @param Request $request
     * @param string $mensaId
     * @return Response|null
     */
    public function signup(Request $request, string $mensaId): ?JsonResponse
    {
        /** @var Mensa $mensa */
        $mensa = Mensa::findOrFail($mensaId);

        $user = $this->currentUser();
        $isAdmin = $user != null && $user->mensa_admin;

        /* Check if the email is valid */
        $validator = Validator::make($request->all(), [
            'email' => ['string', 'email:rfc,dns', 'required'],
            'signup_id' => ['exists:signup,signup_id'],
            'signups' => ['required'],
            'override_user_limit' => [$isAdmin ? 'boolean' : 'prohibited']
        ]);
        if ($validator->fails()) {
            return response()->json([
                "error_code" => ErrorMessages::GENERAL_VALIDATION_ERROR,
                "errors" => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }
        $signupUser = $this->lookupUser($request->get('email'));
        $overrideUserLimit = $request->get('override_user_limit', false);
        $email = $request->get('email');

        // If there is a previous signup, the id's need to match so we know the overriding is on purpose
        $previousSignups = Signup::whereMensaId($mensaId)->whereUserId($signupUser->id);
        if ($previousSignups->count() > 0) {
            if ($request->isMethod(Request::METHOD_POST)) {
                abort(Response::HTTP_BAD_REQUEST);
                throw new Error(); // For lint
            }
        } else {
            if ($request->isMethod(Request::METHOD_PUT)) {
                abort(Response::HTTP_BAD_REQUEST);
                throw new Error(); // For lint
            }
        }

        if ($previousSignups->count() > 0
            && (
                !$request->has('signup_id')
                || $request->get('signup_id') != $previousSignups->first()->signup_id
            )
        ) {
            return response()->json([
                "error_code" => ErrorMessages::SIGNUP_IDS_NOT_MATCHING
            ], Response::HTTP_BAD_REQUEST);
        }
        $signupId = $previousSignups->first()?->signup_id ?? Str::uuid();
        $confirmationId = $previousSignups->first()?->confirmation_code ?? Str::uuid();
        $isConfirmed = $isAdmin ?: $previousSignups->first()?->confirmed ?? $user?->email == $email;

        /* Here we map all the signups */
        /** @var Signup[] $signups */
        $signups = [];
        $errors = new MessageBag();
        array_map(function (array $data) use ($mensa, $isAdmin, &$signups, &$errors) {
            $signup = $this->getSingleSignup($mensa, $data, $isAdmin);
            if ($signup instanceof MessageBag) {
                $errors = $errors->merge($signup);
            } else {
                $signups[] = $signup;
            }
        }, $request['signups']);

        if ($errors->isNotEmpty()) {
            return response()->json([
                "error_code" => ErrorMessages::GENERAL_VALIDATION_ERROR,
                "errors" => $errors->messages()
            ], Response::HTTP_BAD_REQUEST);
        }

        /* Some sanity checks */
        // Would it still fit in the mensa? (mensaCurrent - previousSignups + newSignups <= mensaMax)
        if (
            $overrideUserLimit &&
            $mensa->users_count - $previousSignups->count() + count($signups) > $mensa->max_users
        ) {
            return response()->json([
                "error_code" => ErrorMessages::SIGNUP_MAX_USERS_REACHED
            ]);
        }

        // 1, and only 1 main user:
        $mainCount = count(array_filter($signups, function (Signup $signup) {
            return !$signup->is_intro;
        }));
        if ($mainCount != 1) {
            return response()->json([
                "error_code" => ErrorMessages::SIGNUP_ONE_MAIN_ONLY
            ], Response::HTTP_BAD_REQUEST);
        }
        // intros can't be cooks or washing dishes
        foreach ($signups as $signup) {
            if ($signup->is_intro && ($signup->cooks || $signup->dishwasher)) {
                return response()->json([
                    "error_code" => ErrorMessages::SIGNUP_INTRO_DISHES_COOKS
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        /* And persist to the db */
        // Delete previous signups
        $previousSignups->delete();
        // Save new signups
        foreach ($signups as $signup) {
            $signup->mensa()->associate($mensa);
            $signup->user()->associate($signupUser);
            $signup->signup_id = $signupId;
            $signup->confirmation_code = $confirmationId;
            $signup->confirmed = $isConfirmed;
            $signup->save();
        }

        return null;
    }

    private function getSingleSignup(Mensa $mensa, array $userData, $isAdmin): Signup|MessageBag
    {
        $allowedFoodOptions = $this->mapFoodOptionsFromIntToNames($mensa->food_options);

        $rules = [
            'foodOption' => ['string', Rule::in($allowedFoodOptions), 'required'],
            'isIntro' => ['boolean', 'required'],
            'allergies' => ['string', 'nullable'],
            'extraInfo' => ['string', 'nullable'],
        ];

        // Intros are not allowed to be cooks or dishwashers. Main users require a dishwasher, and admins can use cooks
        if ($userData['isIntro']) {
            $rules = array_merge($rules, [
                'cooks' => ['prohibited'],
                'dishwasher' => ['prohibited'],
            ]);
        } else {
            $rules = array_merge($rules, [
                'cooks' => ['boolean', $isAdmin ? 'nullable' : 'prohibited'],
                'dishwasher' => ['boolean', 'required'],
            ]);
        }

        $validator = Validator::make($userData, $rules);
        if ($validator->fails()) return $validator->errors();

        $signup = new Signup();
        $signup->cooks = $userData['cooks'] ?? false;
        $signup->dishwasher = $userData['dishwasher'] ?? false;
        $signup->food_option = $this->mapFoodOptionFromNameToInt($userData['foodOption']);
        $signup->is_intro = $userData['isIntro'];
        $signup->allergies = $userData['allergies'] ?: "";
        $signup->extra_info = $userData['extraInfo'] ?: "";

        return $signup;
    }

    public function confirmSignup(Request $request, string $mensaId, string $signupId): ?JsonResponse
    {
        $signup = Signup::findOrFail($signupId);
        if ($signup->mensa_id != $mensaId) {
            return response()->json([
                "error_code" => ErrorMessages::SIGNUP_NOT_EXIST
            ], Response::HTTP_NOT_FOUND);
        }

        $currentUser = $this->currentUser();
        Gate::forUser($currentUser)->authorize('canEdit', [$signup, $request->get('confirmation_code')]);

        if ($signup->confirmed) {
            return response()->json([
                "error_code" => ErrorMessages::SIGNUP_ALREADY_CONFIRMED
            ], Response::HTTP_BAD_REQUEST);
        }

        $signup->confirmed = true;
        $signup->save();

        return null;
    }

    public function deleteSignup(Request $request, string $mensaId, string $signupId): ?JsonResponse
    {
        $signup = Signup::findOrFail($signupId);
        if ($signup->mensa_id != $mensaId) {
            return response()->json([
                "error_code" => ErrorMessages::SIGNUP_NOT_EXIST
            ], Response::HTTP_NOT_FOUND);
        }

        $currentUser = $this->currentUser();
        Gate::forUser($currentUser)->authorize('canEdit', [$signup, $request->get('confirmation_code')]);

        $signup->delete();
        return null;
    }

    private function currentUser(): ?User
    {
        try {
            return $this->remoteLookup->currentUpdatedIfNecessary();
        } catch (ClientExceptionInterface) {
            abort(Response::HTTP_BAD_GATEWAY);
        }
        return null; // For lint
    }

    /**
     * Convenience function to check if there already exists signups for this user
     *
     * @param Mensa $mensa
     * @param string|User $userReference
     * @return Signup[]
     * @throws HttpException
     */
    private function lookupSignups(Mensa $mensa, string|User $userReference): array
    {
        if ($userReference instanceof User) {
            $user = $userReference;
        } else {
            $user = $this->lookupUser($userReference);
        }

        return Signup::where('mensa_id', '=', $mensa->id)
            ->where('user_id', '=', $user->id)
            ->get()
            ->all();
    }

    /**
     * Convenience function to lookup a user, and throw an error if it doesn't exist
     *
     * @param string $userReference
     * @return User
     * @throws HttpException
     */
    private function lookupUser(string $userReference): User
    {
        try {
            $user = $this->remoteLookup->lookLocalFirst($userReference);

            if ($user == null) {
                abort(Response::HTTP_BAD_REQUEST, "User doesn't exist");
            }

            return $user;
        } catch (ClientExceptionInterface) {
            abort(Response::HTTP_BAD_GATEWAY);
            throw new Error(); // For lint
        }
    }
}