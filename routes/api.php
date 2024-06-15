<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegistrationController;
use App\Http\Controllers\auth\VerifyotpController;
use App\Http\Controllers\testAPis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Test Routes
Route::post('otpsender', [testAPis::class, 'Otp']);  // Test that otpis sending

//Auth routes

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [RegistrationController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
    Route::get('resendotp', [VerifyotpController::class, 'ResendOtp']);
    Route::post('verifyotp', [VerifyotpController::class, 'VerifyOtp']);
});



