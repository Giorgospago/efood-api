<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Coupon;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $orders = $user->orders()
            ->with(['products'])
            ->orderByDesc('created_at')
            ->get();

        $response = [
            'success' => true,
            'message' => 'List of my orders',
            'data' => [
                'orders' => $orders
            ]
        ];
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $address = $user->addresses()->find($request->address_id);
        $store = Store::find($request->store_id);

        if (!$address) {
            $response = [
                'success' => false,
                'message' => 'Address not found'
            ];
            return response()->json($response, 404);
        }

        if (!$store) {
            $response = [
                'success' => false,
                'message' => 'Store not found'
            ];
            return response()->json($response, 404);
        }

        $distanceInKm = DB::select("SELECT distance({$store->latitude},{$store->longitude},{$address->latitude},{$address->longitude}) as distance")[0]->distance;
        if ($store->delivery_range < $distanceInKm) {
            $response = [
                'success' => false,
                'message' => 'Address out of delivery range'
            ];
            return response()->json($response, 400);

        }

        $order = new Order();
        $order->user_id = $user->id;
        $order->store_id = $store->id;
        $order->address_id = $address->id;
        $order->payment_method = $request->payment_method;
        $order->shipping_method = $request->shipping_method;
        $order->note = $request->note;
        $order->tip = $request->tip;
        $order->save();

        /**
         * Check Products
         */
        $order->products_price = 0;
        foreach ($request->products as $p) {
            $product = $store->products()->find($p['product_id']);
            if (!$product) {
                $response = [
                    'success' => false,
                    'message' => 'Some products not found'
                ];
                return response()->json($response, 404);
            }

            $orderProduct = new OrderProduct();
            $orderProduct->product_id = $product->id;
            $orderProduct->product_name = $product->name;
            $orderProduct->note = $p['note'];
            $orderProduct->quantity = $p['quantity'];
            $orderProduct->price = $product->price;
            $orderProduct->total_price = $product->price * $p['quantity'];
            $order->products_price += $orderProduct->total_price;
            $order->products()->save($orderProduct);
        }
        $order->save();

        /**
         * Calculate delivery_time and shipping_price
         */
        $minPerStoreOrder = config('app.delivery_time.minutes_per_store_order');
        $minPerItem = config('app.delivery_time.minutes_per_item');
        $minPerKm = config('app.delivery_time.minutes_per_km');
        // $minPerDriverOrder = config('app.delivery_time.minutes_per_driver_order');
        
        $storeOrdersCount = $store->orders()
            ->whereIn('status', ['pending', 'processing', 'out_for_delivery'])
            ->count();
        $orderProductsCount = $order->products()->count();
        $shippingPriceFixed = config('app.shipping_price.fixed');
        $shippingPricePerKm = config('app.shipping_price.price_per_km');
        
        $order->delivery_time = ($minPerStoreOrder * $storeOrdersCount) + ($minPerItem * $orderProductsCount) + ($minPerKm * $distanceInKm);
        $order->shipping_price = $shippingPriceFixed + ($shippingPricePerKm * $distanceInKm);

        
        /**
         * Check Payment Method
         */
        if ($order->payment_method === 'card') {
            // payment_id
        }

        /**
         * Check Coupon discount
         */
        if ($request->has('coupon_code')) {
            $couponIsValid = true;
            $coupon = Coupon::where('code', $request->coupon_code)
                ->where('active', true)
                ->first();

            if (!$coupon) {
                $couponIsValid = false;
            } else {
                if ($coupon->start_date && $coupon->start_date->isFuture()) {
                    $couponIsValid = false;
                }
    
                if ($coupon->end_date && $coupon->end_date->isPast()) {
                    $couponIsValid = false;
                }
            }

            if ($couponIsValid) {
                $order->coupon_code = $coupon->code;
                if ($coupon->type === 'percentage') {
                    $order->discount = $order->products_price * ($coupon->value / 100);
                } else {
                    $order->discount = $coupon->value;
                }
                $order->save();
            }
        }

        $order->total_price = $order->products_price + $order->shipping_price - $order->discount + $order->tip;
        $order->save();

        $response = [
            'success' => true,
            'message' => 'Order created',
            'data' => [
                'order' => $order->refresh()
            ]
        ];
        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $order = $user->orders()->find($id);

        if (!$order) {
            $response = [
                'success' => false,
                'message' => 'Order not found'
            ];
            return response()->json($response, 404);
        }

        $response = [
            'success' => true,
            'message' => 'Order details',
            'data' => [
                'order' => $order
            ]
        ];
        return response()->json($response);
    }

}
