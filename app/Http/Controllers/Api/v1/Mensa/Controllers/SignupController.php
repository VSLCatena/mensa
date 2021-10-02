<?php


namespace App\Http\Controllers\Api\v1\Mensa\Controllers;


use App\Contracts\RemoteUserLookup;
use App\Http\Controllers\Api\v1\Mensa\Mappers\SignupMapper;
use App\Models\Mensa;
use App\Models\Signup;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SignupController extends Controller {
    use SignupMapper;

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


    public function getSignups(Request $request, string $mensaId): ?JsonResponse {
        $user = Auth::guard('sanctum')->user() ?? Auth::getUser();

        if ($request->has('confirmation_code')) {
            $signups = Signup::where('mensa_id', '=', $mensaId)
                ->where('confirmation_code', '=', $request->get('confirmation_code'))
                ->get();
        } else if($user != null) {
            $signups = Signup::where('mensa_id', '=', $mensaId)
                ->where('user_id', $user->id)
                ->get();
        } else {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return response()->json($signups->map(function($signup) use ($user) {
            return $this->mapSignup($signup, $user);
        }));
    }

    /**
     * @param Request $request
     * @param string $mensaId
     * @return Response|null
     */
    public function newSignup(Request $request, string $mensaId): ?JsonResponse {
        $mensa = Mensa::findOrFail($mensaId);
        if ($mensa->users_count >= $mensa->max_users) {
            abort(Response::HTTP_BAD_REQUEST, "Mensa is already at its maximum users");
        }


        $validator = Validator::make($request->all(), [
            '*.cooks' => ['boolean'],
            '*.dishwasher' => ['boolean', 'required'],
            '*.food_option' => ['integer', 'required', Rule::in([1, 2, 4])],
            '*.is_intro' => ['boolean', 'required'],
            '*.allergies' => ['string'],
            '*.extra_info' => ['string'],
            '*.user' => ['required', 'string']
        ]);
        if ($validator->fails()) return response()->json([ "errors" => $validator->errors()], Response::HTTP_BAD_REQUEST);


        $user = $this->lookupUser($request->get('user'));
        $currentUser = $this->currentUser();
        $currentUserAdmin = $currentUser != null && $currentUser->mensa_admin;

        $signups = $this->lookupSignups($mensa, $user);
        $mainSignups = array_filter($signups, function($signup) { return $signup->is_intro == false; });
        $introSignups = array_filter($signups, function($signup) { return $signup->is_intro == true; });


        // Create a new signup after validation
        $newSignup = new Signup([
            'id' => Str::uuid(),
            'cooks' => $request->get('cooks', false),
            'dishwasher' => $request->get('dishwasher'),
            'food_option' => $request->get('food_option'),
            'is_intro' => $request->get('is_intro'),
            'allergies' => $request->get('allergies'),
            'extra_info' => $request->get('extra_info'),
            'confirmed' => $currentUser?->id == $user->id || $currentUserAdmin,
            'confirmation_code' => Str::uuid(),
            'user_id' => $user->id,
            'mensa_id' => $mensa->id,
        ]);

        // Check if there already exists a main user, and if so we abort
        if (!$newSignup->is_intro && count($mainSignups) > 0)
            abort(Response::HTTP_BAD_REQUEST, 'Signup already exists');

        // If the user is not a mensa admin, and there is already an intro, we abort
        if ($newSignup->is_intro && count($introSignups) > 0 && !$currentUserAdmin)
            abort(Response::HTTP_BAD_REQUEST, 'Signup already exists');

        // A normal user can't assign himself/someone else as cook
        if ($newSignup->cooks && !$currentUserAdmin)
            abort(Auth::check() ? Response::HTTP_FORBIDDEN : Response::HTTP_UNAUTHORIZED,
                "You don't have the permission to add people as cook");

        $newSignup->save();
        return null;
    }

    /**
     * @param Request $request
     * @param string $mensaId
     * @param string $signupId
     * @return JsonResponse|null
     */
    public function updateSignup(Request $request, string $mensaId, string $signupId): ?JsonResponse {
        $signup = Signup::findOrFail($signupId);
        if ($signup->mensa_id != $mensaId) throw new ModelNotFoundException();

        $currentUser = $this->currentUser();
        $currentUserAdmin = $currentUser != null && $currentUser->mensa_admin;

        Gate::forUser($currentUser)->authorize('canEdit', [$signup, $request->get('confirmation_code')]);

        $validator = Validator::make($request->all(), [
            'cooks' => ['boolean'],
            'dishwasher' => ['boolean'],
            'food_option' => ['integer', Rule::in([1, 2, 4])],
            'allergies' => ['string'],
            'extra_info' => ['string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }


        if ($request->has('cooks')) {
            if (!$currentUserAdmin)
                abort($currentUser != null ? Response::HTTP_UNAUTHORIZED : Response::HTTP_FORBIDDEN);

            $signup->cooks = $request->get('cooks');
        }

        if ($request->has('dishwasher')) $signup->dishwasher = $request->get('dishwasher');
        if ($request->has('food_option')) $signup->food_option = $request->get('food_option');
        if ($request->has('allergies')) $signup->allergies = $request->get('allergies');
        if ($request->has('extra_info')) $signup->extra_info = $request->get('extra_info');

        $signup->save();
        return null;
    }

    public function confirmSignup(Request $request, string $mensaId, string $signupId): ?JsonResponse {
        $signup = Signup::findOrFail($signupId);
        if ($signup->mensa_id != $mensaId) throw new ModelNotFoundException();

        $currentUser = $this->currentUser();
        Gate::forUser($currentUser)->authorize('canEdit', [$signup, $request->get('confirmation_code')]);

        if ($signup->confirmed)
            abort(Response::HTTP_BAD_REQUEST, 'Already confirmed');

        $signup->confirmed = true;
        $signup->save();

        return null;
    }

    public function deleteSignup(Request $request, string $mensaId, string $signupId): ?JsonResponse {
        $signup = Signup::findOrFail($signupId);
        if ($signup->mensa_id != $mensaId) throw new ModelNotFoundException();

        $currentUser = $this->currentUser();
        Gate::forUser($currentUser)->authorize('canEdit', [$signup, $request->get('confirmation_code')]);

        $signup->delete();
        return null;
    }


    private function currentUser(): ?User {
        try {
            return $this->remoteLookup->currentUpdatedIfNecessary();
        } catch (ClientExceptionInterface) { abort(Response::HTTP_BAD_GATEWAY); }
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
    private function lookupUser(string $userReference): User {
        try {
            $user = $this->remoteLookup->lookLocalFirst($userReference);

            if ($user == null) {
                abort(Response::HTTP_BAD_REQUEST, "User doesn't exist");
            }

            return $user;
        } catch (ClientExceptionInterface) {
            abort(Response::HTTP_BAD_GATEWAY);
        }
    }
}