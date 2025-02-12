<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Hello Admin!'
    ]);
});

Route::prefix('auth')->middleware("setAuthRole:admin")->group(base_path('routes/auth.php'));

Route::middleware(['auth:sanctum', 'checkRole:admin'])->group(function() {
    
    Route::get('/check', function () {
        return response()->json([
            'message' => 'You are Admin!'
        ]);
    });
    
});