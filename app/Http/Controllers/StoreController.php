<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $lat = $request->coordinates['latitude'];
        $lng = $request->coordinates['longitude'];
        $locale = app()->getLocale();

        $cached = Cache::rememberForever("stores.$locale", function() {
            $stores = Store::query()
                ->with([
                    'categories' => function ($subQuery) {
                        $subQuery->select('categories.id', 'categories.name');
                    }
                ])
                ->select(
                    'id',
                    'name',
                    'address',
                    'delivery_range',
                    'latitude',
                    'longitude',
                    'working_hours',
                    'minimum_cart_value',
                    'phone'
                )
                ->where('active', true)
                ->get();
            $stores->each->append(["logo", "cover"]);

            return $stores->toArray();
        });

        $shippingPriceFixed = config('app.shipping_price.fixed');
        $shippingPricePerKm = config('app.shipping_price.price_per_km');

        $stores = collect($cached)
            ->map(function ($store) use ($lat, $lng, $shippingPriceFixed, $shippingPricePerKm) {
                $store["distance"] = $this->distance(
                    $store['latitude'],
                    $store['longitude'],
                    $lat,
                    $lng
                );
                $store["shipping_price"] = round($shippingPriceFixed + ($shippingPricePerKm * ($store["distance"] / 1000)), 1);
                return $store;
            })
            ->filter(function ($store) use ($lat, $lng, $request) {
                $inDistance = $store['distance'] <= $store['delivery_range'] * 1000;
                $inWorkingHours = false;
                $inCategories = false;

                if (!is_null($store['working_hours'])) {
                    $working_hour = $store['working_hours'][now()->dayOfWeek] ?? null;
                    if ($working_hour) {
                        $start = now()
                            ->setTimezone("Europe/Athens")
                            ->setTimeFromTimeString($working_hour['start']);
                        $end = now()
                            ->setTimezone("Europe/Athens")
                            ->setTimeFromTimeString($working_hour['end']);

                        $inWorkingHours = now()->between($start, $end);
                    }
                }

                if ($request->has('categories.0')) {
                    foreach ($request->get('categories') as $categoryId) {
                        if (in_array($categoryId, array_column($store['categories'], 'id'))) {
                            $inCategories = true;
                            break;
                        }
                    }
                } else {
                    $inCategories = true; // If no categories are specified, include all stores
                }

                return $inDistance && $inWorkingHours && $inCategories;
            });

        /* Sorting */
        switch ($request->sort) {
//            case 'distance':
//                $query->orderBy('distance');
//                break;
//            case '-distance':
//                $query->orderByDesc('distance');
//                break;
//            case 'minimum_cart_value':
//                $query->orderBy('minimum_cart_value');
//                break;
//            case '-minimum_cart_value':
//                $query->orderByDesc('minimum_cart_value');
//                break;
//            default:
//                $query->orderBy('distance');
//                break;
        }

        $response = [
            'success' => true,
            'message' => 'List of all stores',
            'data' => [
                'stores' => $stores->values()
            ]
        ];
        return response()->json($response);
    }


    public function show($id, Request $request) {
        $lat = $request->coordinates['latitude'];
        $lng = $request->coordinates['longitude'];

        $query = Store::query();
        $query->select(
            'id',
            'name',
            'address',
            'latitude',
            'longitude',
            'working_hours',
            'minimum_cart_value',
            'phone'
        );

        $query->addSelect(DB::raw('distance(stores.latitude, stores.longitude, ' . $lat . ', ' . $lng . ') as distance'));

        $query->whereId($id);
        $query->where('active', true);
        $query->whereRaw("JSON_EXTRACT(JSON_EXTRACT(working_hours, '$[" . date('w') . "]'), '$.start') <= TIME_FORMAT(NOW(), '%H:%i')");
        $query->whereRaw("JSON_EXTRACT(JSON_EXTRACT(working_hours, '$[" . date('w') . "]'), '$.end') >= TIME_FORMAT(NOW(), '%H:%i')");
        $query->whereRaw('distance(stores.latitude, stores.longitude, ' . $lat . ', ' . $lng . ') <= stores.delivery_range');

        $query->with([
            'productCategories' => function ($subQuery) {
                $subQuery->select(
                    'product_categories.id',
                    'product_categories.name',
                    'product_categories.store_id',
                );
                $subQuery->orderBy('product_categories.sort');
            },
            'productCategories.products' => function ($subQuery) {
                $subQuery->select(
                    'products.id',
                    'products.name',
                    'products.description',
                    'products.price',
                    'products.active',
                    'products.store_id',
                    'products.product_category_id',
                );
                $subQuery->orderBy('products.sort');
            }
        ]);

        $store = $query->first();

        $shippingPriceFixed = config('app.shipping_price.fixed');
        $shippingPricePerKm = config('app.shipping_price.price_per_km');
        $store->shipping_price = round($shippingPriceFixed + ($shippingPricePerKm * $store->distance), 1);

        $store->append(["logo", "cover"]);

        foreach ($store->productCategories as $category) {
            $category->products->each->append(["mainImage"]);
        }

        $response = [
            'success' => true,
            'message' => 'Store details',
            'data' => [
                'store' => $store
            ]
        ];
        return response()->json($response);
    }

    private function distance($lat1, $lon1, $lat2, $lon2) {
        // Earth radius in meters
        $earthRadius = 6371000;

        // Convert degrees to radians
        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);

        // Differences
        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLon = $lon2Rad - $lon1Rad;

        // Haversine formula
        $a = sin($deltaLat / 2) ** 2 +
            cos($lat1Rad) * cos($lat2Rad) *
            sin($deltaLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

}
