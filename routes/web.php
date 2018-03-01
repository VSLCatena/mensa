<?php

Route::get('/', 'HomeController@index')->name('home');
Route::get('/page/{page}', 'HomeController@index')->name('home.page');

// Login and log out
Route::get('login/{token}', 'LoginController@loginByToken');
Route::match(['get', 'post'], 'login', 'LoginController@login')->name('login');
Route::post('logout', 'LoginController@logout')->name('logout');

// Verify confirmation and sign out
Route::get('signin/{code}/confirm', 'ConfirmController@confirm')->name('signin.confirm');
Route::match(['get', 'post'], 'signin/{userId}/edit', 'SigninController@mailSignin')->name('signin.edit');
Route::get('signin/{code}/signout', 'ConfirmController@cancel')->name('signin.cancel');

Route::match(['get', 'post'], 'settings', 'UserSettingsController@changeSettings')->name('user.settings');

Route::prefix('mensa')->group(function() {
    // Mensa editing
    Route::match(['get', 'post'], 'create', 'MensaAdminController@edit')->name('mensa.create');
    Route::match(['get', 'post'], '{id}/edit', 'MensaCookController@edit')->name('mensa.edit');

    // Mensa info
    Route::get('{id}', 'MensaCookController@showOverview')->name('mensa.overview');
    Route::get('{id}/signins', 'MensaCookController@showSignins')->name('mensa.signins');

    // Mensa administration
    Route::post('{mensaId}/togglepaid', 'MensaAdminController@togglePaid')->name('mensa.togglepaid');
    Route::match(['get', 'post'], '{mensaId}/signin/new', 'MensaAdminController@newSignin')->name('mensa.newsignin');
    Route::match(['get', 'post'], '{mensaId}/signin/{lidnummer}/bulk', 'MensaAdminController@bulkSignin')->name('mensa.newsignin.bulk');
    Route::match(['get', 'post'], '{mensaId}/signin/{userId}/edit', 'SigninController@editSignin')->name('mensa.editsignin');
    Route::match(['get', 'post'], '{mensaId}/signin/{userId}/delete', 'MensaAdminController@removeSignin')->name('mensa.removesignin');
    Route::match(['get', 'post'], '{mensaId}/printstate/preview', 'MensaAdminController@printStatePreview')->name('mensa.printstate.preview');
    Route::match(['get', 'post'], '{mensaId}/printstate', 'MensaAdminController@printState')->name('mensa.printstate');
    Route::get('{mensaId}/logs', 'MensaAdminController@showLogs')->name('mensa.logs');

    Route::post('{mensaId}/close', 'MensaAdminController@closeMensa')->name('mensa.close');
    Route::match(['get', 'post'], '{mensaId}/reopen', 'MensaAdminController@openMensa')->name('mensa.open');
    Route::match(['get', 'post'], '{mensaId}/cancel', 'MensaAdminController@cancelMensa')->name('mensa.cancel');

    // Sign in and sign out
    Route::post('search', 'MensaAdminController@requestUserLookup')->name('mensa.searchusers');
    Route::match(['get', 'post'], '{id}/signin/{lidnummer?}', 'SigninController@newSignin')->name('signin');
    Route::post('{id}/signout', 'SigninController@signout')->name('signout');
});

if(config('app.debug', false)){
    Route::get('/mailexample/1', function () {
        $mensaUser = App\Models\MensaUser::where('is_intro', '0')->orderBy('created_at', 'DESC')->firstOrFail();
        return new App\Mail\SigninConformation($mensaUser);
    });

    Route::get('/mailexample/2', function () {
        $mensaUser = App\Models\MensaUser::where('is_intro', '0')->orderBy('created_at', 'DESC')->firstOrFail();
        return new App\Mail\SigninConfirmed($mensaUser);
    });

    Route::get('/mailexample/3', function() {
        $mensaUser = App\Models\MensaUser::where('is_intro', '0')->orderBy('created_at', 'DESC')->firstOrFail();
        return new App\Mail\SigninCancelled($mensaUser);
    });

    Route::get('/mailexample/4', function() {
        $mensaUser = App\Models\MensaUser::where('is_intro', '0')->orderBy('created_at', 'DESC')->firstOrFail();
        return new App\Mail\MensaCancelled($mensaUser);
    });
}