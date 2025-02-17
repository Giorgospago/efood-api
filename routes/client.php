<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\StoreController;

Route::get('/', function () {
    return response()->json([
        'message' => 'Hello Client!'
    ]);
});

Route::prefix('auth')->group(base_path('routes/auth.php'));

Route::get("categories", [CategoryController::class, 'index']);
Route::get("stores", [StoreController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function() {
    Route::prefix("users")
        ->controller(UserController::class)
        ->group(function(){
            Route::get("me", 'me');
            Route::get("tokens", 'tokens');
            Route::delete("revoke-all-tokens", 'revokeAllTokens');
        });

    Route::prefix("addresses")
        ->controller(AddressController::class)
        ->group(function() {
            Route::get("", 'index');
            Route::post("", 'store');
            Route::get("{id}", 'show');
            Route::delete("{id}", 'destroy');
        });
    
});
