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
Route::match(['get', 'post'], '/login', 'LoginController@login')->name('login');
Route::post('/logout', 'LoginController@logout')->name('logout');

Route::get('/signup', function(){ return redirect(route('home')); });
Route::post('/signup', 'SignupController@signup')->name('signup');
Route::get('signout', function(){ return redirect(route('home')); });
Route::post('/signout', 'SignupController@signout')->name('signout');

Route::prefix('mensa')->group(function() {
    Route::match(['get', 'post'], 'create', 'MensaController@edit')->name('mensa.create');
    Route::get('edit', function () { return redirect(route('home')); });
    Route::post('edit', 'MensaController@edit')->name('mensa.edit');
    Route::get('overview', function () { return redirect(route('home')); });
    Route::post('overview', 'MensaController@showOverview')->name('mensa.overview');
    Route::get('overview/signins', function() { return redirect(route('home')); });
    Route::post('overview/signins', 'MensaController@showSignins')->name('mensa.overview.signins');
    Route::post('togglepaid', 'MensaController@togglePaid')->name('mensa.togglepaid');
});