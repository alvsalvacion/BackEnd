<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/currentUser', [AuthController::class, 'fetchCurrentUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/register', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgotPassword', [AuthController::class, 'forgotPassword']);
Route::post('/updatePassword', [AuthController::class, 'updatePassword']);
Route::get('/changePassword/{userId}', [AuthController::class, 'changePassword'])->name('change.password');
Route::get('/verifyEmail/{userId}', [AuthController::class, 'verifyEmail'])->name('verify.email');
