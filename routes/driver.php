<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriverOrderController;

Route::get('/', function () {
    return response()->json([
        'message' => 'Hello Driver!'
    ]);
});

Route::prefix('auth')->middleware("setAuthRole:driver")->group(base_path('routes/auth.php'));

Route::middleware(['auth:sanctum', 'checkRole:driver'])->group(function() {
    
    Route::prefix("orders")
        ->controller(DriverOrderController::class)
        ->group(function() {
            Route::get("nearby", 'nearbyOrders');
            Route::post("take", 'takeOrder');
            Route::get("details/{id}", 'orderDetails');
            Route::post("start-delivery", 'startDelivery');
            Route::post("complete-payment", 'completePayment');
            Route::post("complete-delivery", 'completeDelivery');
        });
});