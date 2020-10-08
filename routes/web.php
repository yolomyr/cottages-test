<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerificationController;
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

Route::get('/verify/user', [VerificationController::class, 'verify']);
Route::get('/change/email', [VerificationController::class, 'confirmEmailChange']);
Route::get('/approve/booking', [VerificationController::class, 'approveBooking']);

Route::get('/mail/template/verify', function () {
    return new App\Mail\UserConfirmed([
        'user_name' => 'Петр',
        'user_surname' => 'Петров',
        'user_email' => 'awdaw@asdad.qwe',
        'user_password' => 'asdasd123'
    ]);
});
