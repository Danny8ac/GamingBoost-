<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BoostController;

Route::get('/ping', fn () => response()->json(['ok' => true]));

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
	Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store']);
	Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index']);
	Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show']);
	Route::post('/orders/{order}/pay', [\App\Http\Controllers\OrderController::class, 'pay']);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    // Boosts
    Route::get('/boosts', [BoostController::class, 'index']);
    Route::post('/boosts/{id}/buy', [BoostController::class, 'buy']);
    Route::get('/my-boosts', [BoostController::class, 'myBoosts']);
});