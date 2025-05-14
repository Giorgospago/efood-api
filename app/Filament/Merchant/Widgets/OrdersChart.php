<?php

namespace App\Filament\Merchant\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $orders = Order::query()
            ->whereRelation("store", "user_id", auth()->id())
            ->get();
        return [
            'datasets' => [
                [
                    'label' => 'Orders per month',
                    'data' => [
                        $orders->filter(function ($order) {
                            return Carbon::parse($order->created_at)->month === 1;
                        })->count(),
                        $orders->filter(function ($order) {
                            return Carbon::parse($order->created_at)->month === 2;
                        })->count(),
                        $orders->filter(function ($order) {
                            return Carbon::parse($order->created_at)->month === 3;
                        })->count(),
                        $orders->filter(function ($order) {
                            return Carbon::parse($order->created_at)->month === 4;
                        })->count(),
                        $orders->filter(function ($order) {
                            return Carbon::parse($order->created_at)->month === 5;
                        })->count(),
                        $orders->filter(function ($order) {
                            return Carbon::parse($order->created_at)->month === 6;
                        })->count(),
                        $orders->filter(function ($order) {
                            return Carbon::parse($order->created_at)->month === 7;
                        })->count(),
                        $orders->filter(function ($order) {
                            return Carbon::parse($order->created_at)->month === 8;
                        })->count(),
                        $orders->filter(function ($order) {
                            return Carbon::parse($order->created_at)->month === 9;
                        })->count(),
                        $orders->filter(function ($order) {
                            return Carbon::parse($order->created_at)->month === 10;
                        })->count(),
                        $orders->filter(function ($order) {
                            return Carbon::parse($order->created_at)->month === 11;
                        })->count(),
                        $orders->filter(function ($order) {
                            return Carbon::parse($order->created_at)->month === 12;
                        })->count(),
                    ]
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
