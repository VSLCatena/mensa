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
use Illuminate\Support\Facades\Log;

class GenerateTokenController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @param RemoteUserLookup $userLookup
     */
    public function __construct(private RemoteUserLookup $userLookup)
    {
        $this->systemUser = User::where('name', 'SYSTEM')->first();
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
            Log::error([
                "category" => "auth",
                "text" => "HTTP_BAD_GATEWAY",
                "user_id" =>$this->systemUser->id,
                "object_id" =>$user->id
                
            ]);             
            abort(Response::HTTP_BAD_GATEWAY);
        }

        if ($user == null) {
            Log::error([
                "category" => "auth",
                "text" => "HTTP_UNAUTHORIZED",
                "user_id" =>$this->systemUser->id,
                "object_id" =>$user->id
                
            ]);            
            abort(Response::HTTP_UNAUTHORIZED);
        }
        Log::info([
            "category" => "auth",
            "text" => "Success",
            "user_id" =>$this->systemUser->id,
            "object_id" =>$user->id
            
        ]);
        return response()->json([
            'token' => $user->createToken(Str::uuid())->plainTextToken
        ]);
    }
}
