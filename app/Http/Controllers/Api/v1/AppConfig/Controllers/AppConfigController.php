<?php

namespace App\Http\Controllers\Api\v1\AppConfig\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AppConfigController extends Controller
{
    /** Create a new controller instance.*/
    public function __construct()
    {
    }

    /**  Get a list of mensas */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'defaultMensaOptions' => [
                'title' => 'Mensa met betaalde afwas',
                'maxSignups' => 42,
                'price' => 4.00,
            ],
        ]);
    }
}
