<?php

use App\Http\Controllers\SocketController;

Route::middleware('auth.socket')->group(function () {
    Route::controller(SocketController::class)->group(function () {
        Route::post('driver-location', 'driverLocation');
        Route::post('set-user-socket', 'setUserSocket');
        Route::delete('delete-user-socket', 'deleteUserSocket');
        Route::delete('delete-all-sockets', 'deleteAllSockets');
    });
});