<?php

namespace App\Http\Controllers\Api\v1\User\Controllers;

use App\Contracts\RemoteUserLookup;
use App\Models\User;
use App\Models\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class GenerateTokenController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @param RemoteUserLookup $userLookup
     */
    public function __construct(private RemoteUserLookup $userLookup)
    {
    }

    /**
     * Get a single mensa
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $azureUser = Socialite::driver('azure')->stateless()->user();

        $user = User::find($azureUser->id) ?? new User();

        if (!$user->exists)
            $user->id = $azureUser->id;

        try {
            $user = $this->userLookup->getUpdatedUser($user, $azureUser->principal_name);
        } catch (ClientExceptionInterface) {
            $log = new Log;
            $log->category = "GenerateTokenController";
            $log->user_id = "SYSTEM";
            $log->object_id = $azureUser->id;
            $log->text = "userLookup->getUpdatedUser failed with ". $user . " (" . $azureUser->principal_name . ")";
            $log->save();
            abort(Response::HTTP_BAD_GATEWAY);
        }

        if ($user == null) {
            $log = new Log;
            $log->category = "GenerateTokenController";
            $log->user_id = "SYSTEM";
            $log->object_id = $azureUser->id;
            $log->text = "userLookup->getUpdatedUser not found for" . $azureUser->principal_name;
            $log->save();
            abort(Response::HTTP_UNAUTHORIZED);
        }
        $log = new Log;
        $log->category = "GenerateTokenController";
        $log->user_id = "SYSTEM";
        $log->object_id = $azureUser->id;
        $log->text = "userLookup->getUpdatedUser. Token created for" . $user . " (" . $azureUser->principal_name . ")";
        $log->save();
        return response()->json([
            'token' => $user->createToken(Str::uuid())->plainTextToken
        ]);
    }
}
