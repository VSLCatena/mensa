<?php

namespace App\Http\Controllers\Api\v1\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class GetAuthorizationUrlController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->systemUser = User::where('name', 'SYSTEM')->first();        
    }

    /**
     * Get a single mensa
     *
     * Url: mensa/[uuid]
     *
     * @param Request $request
     * @param $mensaId
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        Log::info([
            "category" => "auth",
            "text" => "GetAuthorizationUrl",
            "user_id" =>$this->systemUser->id,
            "object_id" =>$this->systemUser->id
            
        ]);   
        return response()->json([
            "authorizationUri" => Socialite::driver('azure')
                ->stateless()->redirect()->getTargetUrl()
        ]);
    }
}

