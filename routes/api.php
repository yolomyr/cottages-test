<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;

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

// user registration
Route::post('register', [RegisterController::class, 'register']);
Route::post('/password/reset/send', [RegisterController::class, 'passwordResetNotification']);
Route::post('/password/reset', [RegisterController::class, 'passwordReset']);

// join trait requests
Route::post('/directory/user/joinable', [UserController::class, 'joinModels']);
Route::post('/directory/single', [DirectoryController::class, 'getSingle']);

// authentication routes group
Route::group([
    'prefix' => 'auth'
], static function() {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group([
    'prefix' => 'profile'
], static function() {
    Route::post('change/email', [ProfileController::class, 'changeEmail']);
    Route::post('change/password', [ProfileController::class, 'changePassword']);
    Route::post('change', [ProfileController::class, 'change']);
});

Route::group([
    'prefix' => 'admin'
], static function() {
    Route::post('verify/user', [AdminController::class, 'verifyUser']);
});

Route::group([
    'prefix' => 'news'
], static function() {
    Route::post('', [NewsController::class, 'index']);
    Route::post('single', [NewsController::class, 'single']);
    Route::post('create', [NewsController::class, 'create']);
    Route::post('update', [NewsController::class, 'update']);
    Route::post('delete', [NewsController::class, 'delete']);
    Route::post('delete/file', [NewsController::class, 'deleteFile']);
});

Route::group([
    'prefix' => 'service'
], static function() {
    Route::post('', [ServiceController::class, 'index']);
    Route::post('single', [ServiceController::class, 'single']);
    Route::post('create', [ServiceController::class, 'create']);
    Route::post('update', [ServiceController::class, 'update']);
});

Route::group([
    'prefix' => 'booking'
], static function() {
    Route::post('', [BookingController::class, 'index']);
    Route::post('create', [BookingController::class, 'create']);
    Route::post('delete', [BookingController::class, 'delete']);
    Route::post('user/get', [BookingController::class, 'getUsersBookings']);
    Route::post('all/get', [AdminController::class, 'getAllBookings']);
});
