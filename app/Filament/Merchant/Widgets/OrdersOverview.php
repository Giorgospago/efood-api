<?php

namespace App\Filament\Merchant\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrdersOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $orders = Order::query()
            ->whereRelation("store", "user_id", auth()->id())
            ->get();

        return [
            Stat::make("Total orders", $orders->count()),
            Stat::make("Total order price", $orders->sum("total_price")."€"),
            Stat::make("Orders this week", $orders->where("created_at", ">=", now()->startOfWeek())->count()),
            Stat::make("Order price this week", $orders->where("created_at", ">=", now()->startOfWeek())->sum("total_price")."€"),
        ];
    }
}
