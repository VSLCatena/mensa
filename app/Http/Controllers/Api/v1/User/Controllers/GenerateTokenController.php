<?php

namespace App\Http\Controllers\Api\v1\User\Controllers;

use App\Contracts\RemoteUserLookup;
use App\Models\User;
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
            abort(Response::HTTP_BAD_GATEWAY);
        }

        if ($user == null) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'token' => $user->createToken(Str::uuid())->plainTextToken
        ]);
    }
}
