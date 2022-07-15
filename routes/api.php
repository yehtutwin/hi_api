<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\PassportAuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\NotificationController;
 
Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [PassportAuthController::class, 'logout']);
    Route::get('get-user', [PassportAuthController::class, 'userInfo']);
 
    Route::resource('users', UserController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('bookings', BookingController::class);

    Route::post('/notification/booking-done', [NotificationController::class, 'bookingDone']);
    Route::post('/notification/invoice-paid', [NotificationController::class, 'invoicePaid']);
});

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
