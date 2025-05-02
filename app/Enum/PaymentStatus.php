<?php
namespace App\Enum;

enum PaymentStatus: string
{
    case pending = "pending";
    case completed = "completed";
    case failed = "failed";

    public function color(): string {
        return match ($this) {
            self::pending => "warning",
            self::completed => "success",
            self::failed => "danger",
            default => "secondary",
        };
    }
}
