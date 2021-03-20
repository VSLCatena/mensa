<?php

use App\Http\Controllers\Api\Mensa\MensaController;
use Illuminate\Http\Request;

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

Route::get('mensa/list', 'Api\Mensa\Controllers\GetMensaListController@getMensas');
Route::get('mensa/{mensaId}', 'Api\Mensa\Controllers\GetMensaController@getMensa');

Route::fallback(function () {
    return "Invalid API call";
});