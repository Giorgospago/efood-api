<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return response()->json([
        'message' => 'Hello Client!'
    ]);
});

Route::prefix('auth')->group(base_path('routes/auth.php'));

Route::middleware(['auth:sanctum'])->group(function() {
    Route::prefix("users")->group(function(){
        Route::get("me", [UserController::class, 'me']);
        Route::get("tokens", [UserController::class, 'tokens']);
        Route::delete("revoke-all-tokens", [UserController::class, 'revokeAllTokens']);
    });
});