<?php

namespace App\Http\Controllers;

use App\Enum\RoleCode;
use App\Models\Order;
use App\Models\User;
use App\Models\UserSocket;
use Illuminate\Http\Request;

class SocketController extends Controller
{

    public function setUserSocket(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'socket_id' => 'required|string',
        ]);

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        UserSocket::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'socket_id' => $request->socket_id
            ],
            [
                'user_id' => $request->user_id,
                'socket_id' => $request->socket_id
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Socket ID updated successfully',
        ]);
    }

    public function deleteUserSocket(Request $request)
    {
        $request->validate([
            'socket_id' => 'required|string',
        ]);

        UserSocket::where('socket_id', $request->socket_id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Socket ID deleted successfully',
        ]);
    }

    public function deleteAllSockets(Request $request)
    {
        UserSocket::truncate();

        return response()->json([
            'success' => true,
            'message' => 'All socket IDs deleted successfully',
        ]);
    }

    public function driverLocation(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $driver = User::find($request->driver_id);
        if (!$driver->roles()->where('role_id', RoleCode::driver)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // $user_ids = Order::query()
        //     ->where('driver_id', $driver->id)
        //     ->whereStatus('out_for_delivery')
        //     ->pluck('user_id')
        //     ->toArray();

        // $socket_ids = UserSocket::whereIn('user_id', $user_ids)
        //     ->pluck("socket_id")
        //     ->toArray();

        $results = Order::query()
            ->select('orders.id as order_id', 'user_sockets.socket_id')
            ->where('orders.driver_id', $driver->id)
            ->where('orders.status', 'out_for_delivery')
            ->join('user_sockets', 'orders.user_id', '=', 'user_sockets.user_id')
            ->get();

        $socket_map = [];
        foreach ($results as $result) {
            $socket_map[$result->order_id][] = $result->socket_id;
        }

        if (count(array_keys($socket_map)) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No sockets found',
            ]);
        }

        $data = [];
        foreach ($socket_map as $order_id => $socket_ids) {
            $data[] = [
                'socket_ids' => $socket_ids,
                'channel' => 'driver-tracking-' . $order_id,
                'data' => [
                    'driver_id' => $request->driver_id,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ],
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Sockets found',
            'data' => $data
        ]);

    }
}
