<?php
namespace App\Enum;

enum OrderStatus: string
{
    case pending = "pending";
    case processing = "processing";
    case outForDelivery = "out_for_delivery";
    case completed = "completed";
    case cancelled = "cancelled";

    public function color(): string {
        return match ($this) {
            self::pending => "warning",
            self::processing, self::outForDelivery => "info",
            self::completed => "success",
            self::cancelled => "danger",
            default => "secondary",
        };
    }
}
