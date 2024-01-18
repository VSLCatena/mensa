<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/page/{page}', [HomeController::class, 'index'])->name('home.page');

Route::get('faq', [FaqController::class, 'view'])->name('faq');
Route::get('faq/list', [FaqController::class, 'listFaqs'])->name('faq.list');
Route::match(['get', 'post'], 'faq/add',  [FaqController::class, 'edit'])->name('faq.add');
Route::match(['get', 'post'], 'faq/edit/{id}',  [FaqController::class, 'edit'])->name('faq.edit');
Route::match(['get', 'post'], 'faq/delete/{id}',  [FaqController::class, 'delete'])->name('faq.delete');

// Login and log out
Route::get('login/{token}',  [LoginController::class, 'loginByToken']);
Route::match(['get', 'post'], 'login',  [LoginController::class, 'login'])->name('login');
Route::post('logout',  [LoginController::class, 'logout'])->name('logout');

// Verify confirmation and sign out
Route::get('signin/{code}/confirm',  [ConfirmController::class, 'confirm'])->name('signin.confirm');
Route::match(['get', 'post'], 'signin/{userId}/edit', [SigninController::class, 'mailSignin'])->name('signin.edit');
Route::get('signin/{code}/signout',  [ConfirmController::class, 'cancel'])->name('signin.cancel');

Route::match(['get', 'post'], 'settings',  [UserSettingsController::class, 'changeSettings'])->name('user.settings');

Route::prefix('mensa')->group(function() {
    // Mensa editing
    Route::match(['get', 'post'], 'create',  [MensaAdminController::class, 'edit'])->name('mensa.create');
    Route::match(['get', 'post'], '{mensaId}/edit', [MensaCookController::class, 'edit'])->name('mensa.edit');

    // Mensa info
    Route::get('{mensaId}', [MensaCookController::class, 'showOverview'])->name('mensa.overview');
    Route::get('{mensaId}/signins', [MensaCookController::class, 'showSignins'])->name('mensa.signins');

    // Mensa administration
    Route::post('{mensaId}/togglepaid', [MensaAdminController::class, 'togglePaid'])->name('mensa.togglepaid');
    Route::match(['get', 'post'], '{mensaId}/signin/new', [MensaAdminController::class, 'newSignin'])->name('mensa.newsignin');
    Route::match(['get', 'post'], '{mensaId}/signin/{lidnummer}/bulk', [MensaAdminController::class, 'bulkSignin'])->name('mensa.newsignin.bulk');
    Route::match(['get', 'post'], '{mensaId}/signin/{userId}/edit', [SigninController::class, 'editSignin'])->name('mensa.editsignin');
    Route::match(['get', 'post'], '{mensaId}/signin/{userId}/delete', [MensaAdminController::class, 'removeSignin'])->name('mensa.removesignin');
    Route::match(['get', 'post'], '{mensaId}/printstate/preview', [MensaAdminController::class, 'printStatePreview'])->name('mensa.printstate.preview');
    Route::match(['get', 'post'], '{mensaId}/printstate', [MensaAdminController::class, 'printState'])->name('mensa.printstate');
    Route::get('{mensaId}/logs', [MensaAdminController::class, 'showLogs'])->name('mensa.logs');

    Route::post('{mensaId}/close', [MensaAdminController::class, 'closeMensa'])->name('mensa.close');
    Route::match(['get', 'post'], '{mensaId}/reopen', [MensaAdminController::class, 'openMensa'])->name('mensa.open');
    Route::match(['get', 'post'], '{mensaId}/cancel', [MensaCookController::class, 'cancelMensa'])->name('mensa.cancel');

    // Sign in and sign out
    Route::post('search', [MensaAdminController::class, 'requestUserLookup'])->name('mensa.searchusers');
    Route::match(['get', 'post'], '{mensaId}/signin/{lidnummer?}', [SigninController::class, 'newSignin'])->name('signin');
    Route::post('{mensaId}/signout', [SigninController::class, 'signout'])->name('signout');
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