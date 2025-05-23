<?php

use App\Mail\TestMail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $order = \App\Models\Order::find(8);

    return response()->json([
        'message' => 'Welcome to e-food API !!!',
        'total_price' => $order->total_price,
        'total_price_cents' => $order->total_price * 100,
        'amount' => abs($order->total_price * 100)
    ]);
});

Route::get('/test-email', function () {

    for ($i = 0; $i < 100; $i++) {
        Mail::to('info' . $i . '@pagonoudis.gr')
        ->send(new TestMail());
    }

    return response()->json([
        'message' => 'Email sent successfully'
    ]);
});
Route::get('/test', function () {

    $order = \App\Models\Order::find(40);
    $socketIds = $order->user->sockets()->pluck('socket_id')->toArray();
    Http::socket()->post('send-to-client', [
        'socket_ids' => $socketIds,
        'channel' => 'order-update-' . $order->id,
        'data' => [
            'order' => $order
        ]
    ]);

    return response()->json([
        'message' => 'Socket sent successfully'
    ]);
});

Route::get("/roles", function () {
    $roles = \App\Models\Role::all();


    return response()->json([
        "success" => true,
        "data" => [
            "roles" => $roles
        ]
    ]);
});

Route::prefix('driver')->name('driver')->group(base_path('routes/driver.php'));
Route::prefix('client')->name('client')->group(base_path('routes/client.php'));
Route::prefix('sockets')->name('sockets')->group(base_path('routes/sockets.php'));
