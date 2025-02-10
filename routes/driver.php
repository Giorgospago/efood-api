<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Hello Driver!'
    ]);
});

Route::prefix('auth')->middleware("setAuthRole:driver")->group(base_path('routes/auth.php'));

Route::middleware(['auth:sanctum', 'checkRole:driver'])->group(function() {

});