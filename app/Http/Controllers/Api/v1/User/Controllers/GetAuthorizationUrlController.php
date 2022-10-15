<?php

namespace App\Http\Controllers\Api\v1\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;

use App\Models\Log;
use App\Models\User;

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
        $log = new Log;
        $log->category = "GetAuthorizationUrlController";
        $log->user_id = $this->systemUser->id;
        $log->object_id = $this->systemUser->id;
        $log->text = "New AuthorizationUrlRequest from " . $request->getClientIp();
        $log->save();
        return response()->json([
            "authorizationUri" => Socialite::driver('azure')
                ->stateless()->redirect()->getTargetUrl()
        ]);
    }
}

