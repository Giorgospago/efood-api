<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->get();

        $response = [
            'success' => true,
            'message' => 'User addresses',
            'data' => [
                'addresses' => $addresses
            ]
        ];

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'street' => 'required',
            'number' => '',
            'city' => '',
            'postal_code' => '',
            'latitude' => 'required',
            'longitude' => 'required',
            'phone' => '',
            'floor' => '',
            'door' => '',
        ]);
        $address = new Address($request->all());

        $request->user()->addresses()->save($address);

        $response = [
            'success' => true,
            'message' => 'Address created',
        ];
        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $address = $request->user()->addresses()->find($id);
        if (!$address) {
            $response = [
                'success' => false,
                'message' => 'Address not found',
            ];
            return response()->json($response, 404);
        }

        $response = [
            'success' => true,
            'message' => 'Address found successfully',
            'data' => [
                'address' => $address
            ]
        ];

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $address = $request->user()->addresses()->find($id);
        if (!$address) {
            $response = [
                'success' => false,
                'message' => 'Address not found',
            ];
            return response()->json($response, 404);
        }

        $address->delete();
        $response = [
            'success' => true,
            'message' => 'Address deleted',
        ];
        return response()->json($response);
    }
}
