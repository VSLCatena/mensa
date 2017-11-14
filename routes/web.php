<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');

// Login and log out
Route::match(['get', 'post'], 'login', 'LoginController@login')->name('login');
Route::post('logout', 'LoginController@logout')->name('logout');


Route::prefix('mensa')->group(function() {
    // Sign in and sign out
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
    Route::match(['get', 'post'], '{mensaId}/editsignin/{userId}', 'MensaController@editSignin')->name('mensa.editsignin');
    Route::match(['get', 'post'], '{mensaId}/removesignin/{userId}', 'MensaController@removeSignin')->name('mensa.removesignin');
});