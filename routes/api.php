<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix("v1")->group(function(){
    Route::get('login/url', 'Api\v1\User\Controllers\GetAuthorizationUrlController');
    Route::get('login/token', 'Api\v1\User\Controllers\GenerateTokenController');

    Route::get('mensa/list', 'Api\v1\Mensa\Controllers\GetMensaListController');
    Route::get('mensa/{mensaId}', 'Api\v1\Mensa\Controllers\GetMensaController');

    Route::get('user/self', 'Api\v1\User\Controllers\SelfController');
});

Route::fallback(function () {
    return response()->json(["error" => "Invalid API call"]);
});