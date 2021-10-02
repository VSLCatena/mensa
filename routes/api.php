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

Route::prefix("v1")->middleware('jsonRequests')->group(function(){
    Route::get('login/url', 'Api\v1\User\Controllers\GetAuthorizationUrlController');
    Route::get('login/token', 'Api\v1\User\Controllers\GenerateTokenController');

    Route::get('mensas', 'Api\v1\Mensa\Controllers\MensaListController');
    Route::get('mensae', 'Api\v1\Mensa\Controllers\MensaListController');

    Route::post('mensa/new', 'Api\v1\Mensa\Controllers\MensaController@newMensa');
    Route::get('mensa/{mensaId}', 'Api\v1\Mensa\Controllers\MensaController@getMensa');
    Route::patch('mensa/{mensaId}', 'Api\v1\Mensa\Controllers\MensaController@updateMensa');
    Route::delete('mensa/{mensaId}', 'Api\v1\Mensa\Controllers\MensaController@deleteMensa');

    Route::get('mensa/{mensaId}/signups', 'Api\v1\Mensa\Controllers\SignupController@getSignups');
    Route::post('mensa/{mensaId}/signup', 'Api\v1\Mensa\Controllers\SignupController@newSignup');
    Route::patch('mensa/{mensaId}/signup/{signupId}', 'Api\v1\Mensa\Controllers\SignupController@updateSignup');
    Route::patch('mensa/{mensaId}/signup/{signupId}/confirm', 'Api\v1\Mensa\Controllers\SignupController@confirmSignup');
    Route::delete('mensa/{mensaId}/signup/{signupId}', 'Api\v1\Mensa\Controllers\SignupController@deleteSignup');

    Route::get('user/self', 'Api\v1\User\Controllers\SelfController@getSelf');
    Route::patch('user/self/update', 'Api\v1\User\Controllers\SelfController@updateSelf');

});

Route::fallback(function () {
    abort(400);
});