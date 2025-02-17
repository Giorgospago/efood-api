<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $lat = $request->coordinates['latitude'];
        $lng = $request->coordinates['longitude'];

        $stores = Store::query()
            ->select('*')
            ->addSelect(DB::raw('distance(stores.latitude, stores.longitude, ' . $lat . ', ' . $lng . ') as distance'))
            ->where('active', true)
            ->whereRaw("JSON_EXTRACT(JSON_EXTRACT(working_hours, '$[" . date('w') . "]'), '$.start') <= TIME_FORMAT(NOW(), '%H:%i')")
            ->whereRaw("JSON_EXTRACT(JSON_EXTRACT(working_hours, '$[" . date('w') . "]'), '$.end') >= TIME_FORMAT(NOW(), '%H:%i')")
            ->whereRaw('distance(stores.latitude, stores.longitude, ' . $lat . ', ' . $lng . ') <= stores.delivery_range')
            ->orderBy('distance')
            ->get();

        $response = [
            'success' => true,
            'message' => 'List of all stores',
            'data' => [
                'weekDay' => date('w'),
                'stores' => $stores
            ]
        ];
        return response()->json($response);
    }

}
