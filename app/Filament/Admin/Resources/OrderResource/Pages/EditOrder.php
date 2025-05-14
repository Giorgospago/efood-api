<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        $order = $record;
        $order->load([
            "products.product",
            "store",
            "address"
        ]);

        $productsPrice = 0;

        foreach ($order->products as $order_product) {
            $productsPrice += $order_product->total_price;
        }

        $distanceInKm = DB::selectOne("SELECT distance({$order->store->latitude},{$order->store->longitude},{$order->address->latitude},{$order->address->longitude}) as distance")->distance;
        $deliveryTime = 0;
        $shippingPrice = 0;

        if ($order->shipping_method === 'delivery') {
            /**
             * Calculate delivery_time and shipping_price
             */
            $minPerStoreOrder = config('app.delivery_time.minutes_per_store_order');
            $minPerItem = config('app.delivery_time.minutes_per_item');
            $minPerKm = config('app.delivery_time.minutes_per_km');
            // $minPerDriverOrder = config('app.delivery_time.minutes_per_driver_order');

            $storeOrdersCount = $order->store->orders()
                ->whereIn('status', ['processing'])
                // ->whereId('!=', $order->id)
                ->count();
            $orderProductsCount = $order->products()->count();
            $shippingPriceFixed = config('app.shipping_price.fixed');
            $shippingPricePerKm = config('app.shipping_price.price_per_km');

            $deliveryTime = abs(($minPerStoreOrder * $storeOrdersCount) + ($minPerItem * $orderProductsCount) + ($minPerKm * $distanceInKm));
            $shippingPrice = round($shippingPriceFixed + ($shippingPricePerKm * $distanceInKm), 2);
        }

        $totalPrice = $productsPrice + $shippingPrice - $order->discount + $order->tip;
        $order->update([
            "products_price" => $productsPrice,
            "shipping_price" => $shippingPrice,
            "delivery_time" => $deliveryTime,
            "total_price" => $totalPrice
        ]);

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
