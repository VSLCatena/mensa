<?php

Route::get('/', 'HomeController@index')->name('home');

// Login and log out
Route::get('login/{token}', 'LoginController@loginByToken');
Route::match(['get', 'post'], 'login', 'LoginController@login')->name('login');
Route::post('logout', 'LoginController@logout')->name('logout');

// Verify confirmation and sign out
Route::get('signin/{code}/confirm', 'ConfirmController@confirm')->name('signin.confirm');
Route::match(['get', 'post'], 'signin/{userId}/edit', 'SigninController@mailSignin')->name('signin.edit');
Route::get('signin/{code}/signout', 'ConfirmController@cancel')->name('signin.cancel');


Route::prefix('mensa')->group(function() {
    // Sign in and sign out
    Route::post('search', 'MensaController@requestUserLookup')->name('mensa.searchusers');
    Route::match(['get', 'post'], '{id}/signin', 'SigninController@signin')->name('signin');
    Route::post('{id}/signout', 'SigninController@signout')->name('signout');

    // Mensa editing
    Route::match(['get', 'post'], 'create', 'MensaController@edit')->name('mensa.create');
    Route::match(['get', 'post'], '{id}/edit', 'MensaController@edit')->name('mensa.edit');

    // Mensa info
    Route::get('{id}', 'MensaController@showOverview')->name('mensa.overview');
    Route::get('{id}/signins', 'MensaController@showSignins')->name('mensa.signins');

    // Mensa administration
    Route::post('{mensaId}/togglepaid', 'MensaController@togglePaid')->name('mensa.togglepaid');
    Route::match(['get', 'post'], '{mensaId}/signin/new', 'MensaController@newSignin')->name('mensa.newsignin');
    Route::match(['get', 'post'], '{mensaId}/signin/{userId}/edit', 'SigninController@signin')->name('mensa.editsignin');
    Route::match(['get', 'post'], '{mensaId}/signin/{userId}/delete', 'MensaController@removeSignin')->name('mensa.removesignin');
    Route::match(['get', 'post'], '{mensaId}/printstate/preview', 'MensaController@printStatePreview')->name('mensa.printstate.preview');
    Route::match(['get', 'post'], '{mensaId}/printstate', 'MensaController@printState')->name('mensa.printstate');
    Route::get('{mensaId}/logs', 'MensaController@showLogs')->name('mensa.logs');

    Route::post('{mensaId}/close', 'MensaController@closeMensa')->name('mensa.close');
    Route::match(['get', 'post'], '{mensaId}/reopen', 'MensaController@openMensa')->name('mensa.open');
    Route::match(['get', 'post'], '{mensaId}/cancel', 'MensaController@cancelMensa')->name('mensa.cancel');
});

Route::get('/mailexample/1', function () {
    $mensaUser = App\Models\MensaUser::where('is_intro', '0')->orderBy('created_at', 'DESC')->firstOrFail();
    return new App\Mail\SigninConformation($mensaUser);
});

Route::get('/mailexample/2', function () {
    $mensaUser = App\Models\MensaUser::where('is_intro', '0')->orderBy('created_at', 'DESC')->firstOrFail();
    return new App\Mail\SigninConfirmed($mensaUser);
});