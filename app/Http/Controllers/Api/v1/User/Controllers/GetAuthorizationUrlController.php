<?php

namespace App\Http\Controllers\Api\v1\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;

class GetAuthorizationUrlController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get a single mensa
     *
     * Url: mensa/[uuid]
     *
     * @param $mensaId
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'authorizationUri' => Socialite::driver('azure')->with([
                'prompt' => 'select_account',
                'whr' => env("AZURE_DOMAIN"),
                'domain_hint' => env("AZURE_DOMAIN"),
            ])->stateless()->redirect()->getTargetUrl()
        ]);
    }
}
