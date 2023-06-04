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

Route::prefix('v1')->middleware('jsonRequests')->group(function () {
    Route::get('login/url', 'Api\v1\User\Controllers\GetAuthorizationUrlController');
    Route::get('login/token', 'Api\v1\User\Controllers\GenerateTokenController');

    Route::get('mensas', 'Api\v1\Mensa\Controllers\MensaListController');
    Route::get('mensae', 'Api\v1\Mensa\Controllers\MensaListController');

    Route::post('mensa/new', 'Api\v1\Mensa\Controllers\MensaController@newMensa');
    Route::get('mensa/{mensaId}', 'Api\v1\Mensa\Controllers\MensaController@getMensa');
    Route::patch('mensa/{mensaId}', 'Api\v1\Mensa\Controllers\MensaController@updateMensa');
    Route::delete('mensa/{mensaId}', 'Api\v1\Mensa\Controllers\MensaController@deleteMensa');

    Route::get('mensa/{mensaId}/signup', 'Api\v1\Mensa\Controllers\SignupController@getSignup');
    Route::post('mensa/{mensaId}/signup', 'Api\v1\Mensa\Controllers\SignupController@signup');
    Route::put('mensa/{mensaId}/signup', 'Api\v1\Mensa\Controllers\SignupController@signup');
    Route::patch('mensa/{mensaId}/signup/{signupId}/confirm', 'Api\v1\Mensa\Controllers\SignupController@confirmSignup');
    Route::delete('mensa/{mensaId}/signup/{signupId}', 'Api\v1\Mensa\Controllers\SignupController@deleteSignup');

    Route::get('faqs', 'Api\v1\Faq\Controllers\FaqController@getFaqs');
    Route::post('faqs/sort', 'Api\v1\Faq\Controllers\FaqController@sortFaqs');
    Route::post('faq/new', 'Api\v1\Faq\Controllers\FaqController@newFaq');
    Route::put('faq/{faqId}', 'Api\v1\Faq\Controllers\FaqController@updateFaq');
    Route::delete('faq/{faqId}', 'Api\v1\Faq\Controllers\FaqController@deleteFaq');

    Route::get('user/self', 'Api\v1\User\Controllers\SelfController@getSelf');
    Route::patch('user/self', 'Api\v1\User\Controllers\SelfController@updateSelf');

    Route::get('appconfig', 'Api\v1\AppConfig\Controllers\AppConfigController@getDefaultConfig');

});

Route::fallback(function () {
    abort(400);
});
