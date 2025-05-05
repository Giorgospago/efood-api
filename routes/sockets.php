<?php

use App\Http\Controllers\SocketController;

Route::middleware('auth.socket')->group(function () {
    Route::controller(SocketController::class)->group(function () {
        Route::post('driver-location', 'driverLocation');
    });
});