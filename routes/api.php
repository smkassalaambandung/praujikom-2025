<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::get('your-event', [EventController::class, 'yourEvent']);
    Route::apiResource('events', EventController::class);
    Route::get('check-token', function (Request $request) {
        $user = $request->user();
        if ($user) {
            return response()->json([
                'message' => 'Token is valid',
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'error' => 'Token is invalid',
            ], 400);
        }
    });
});
