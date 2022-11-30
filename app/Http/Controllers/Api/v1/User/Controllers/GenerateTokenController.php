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
use App\Models\Log;
use App\Traits\Log\Severity;
use App\Traits\Log\Category;

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
        $log = new Log();
        $log->user_id = $this->systemUser->id; #default value
        $log->severity = Severity::Informational; #default value
        $log->category = Category::mensa;

        $azureUser = Socialite::driver('azure')->stateless()->user();
        $user = User::find($azureUser->id) ?? new User();

        if (!$user->exists)
            $user->id = $azureUser->id;

        try {
            $user = $this->userLookup->getUpdatedUser($user, $azureUser->principal_name);
        } catch (ClientExceptionInterface) {
            $log->user_id = $user->id;
            $log->text = json_encode((object) [
                'result' => "failure",
                'data' => "Updating user failed: HTTP_BAD_GATEWAY"
            ]);             
            $user->Log()->save($log);

            abort(Response::HTTP_BAD_GATEWAY);
        }

        if ($user == null) {
            $log->text = json_encode((object) [
                'result' => "failure",
                'data' => "User not found. HTTP_UNAUTHORIZED".  " from " . $request->getClientIp()
            ]); 
            $this->systemUser->Log()->save($log);    
            abort(Response::HTTP_UNAUTHORIZED);
        }
        $log->user_id = $user->id;
        $log->severity = Severity::Debug;
        $log->text = json_encode((object) [
            'result' => "success",
            'data' => $user->name .  " from " . $request->getClientIp(),
        ]);        
        $this->systemUser->Log()->save($log);    
        
        return response()->json([
            'token' => $user->createToken(Str::uuid())->plainTextToken
        ]);
    }
}
