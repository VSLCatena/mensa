<?php

namespace App\Http\Controllers\Api\v1\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Log;
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
        $log = new Log();
        $log->user_id = $this->systemUser->id;
        $log->severity = '5';
        $log->category = 'mensa';
        $log->text = json_encode((object) [
            'result' => "success",
            'data' => "GetAuthorizationUrl for " . $request->getClientIp(),
        ]);
        $this->systemUser->Log()->save($log);
                
        return response()->json([
            "authorizationUri" => Socialite::driver('azure')
                ->stateless()->redirect()->getTargetUrl()
        ]);
    }
}

