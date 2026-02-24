<?php

use App\Http\Controllers\OrderController;

Route::get('/checkout/{order}', [OrderController::class, 'checkout']);
Route::post('/checkout/{order}/confirm', [OrderController::class, 'confirm']);
Route::post('/checkout/{order}/cancel', [OrderController::class, 'cancel']);