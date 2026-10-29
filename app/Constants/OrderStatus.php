<?php

namespace App\Constants;

class OrderStatus
{
    const PENDING   = 'pending';
    const CONFIRMED = 'confirmed';
    const PREPARING = 'preparing';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';

    public static function all(): array
    {
        return [
            self::PENDING,
            self::CONFIRMED,
            self::PREPARING,
            self::COMPLETED,
            self::CANCELLED,
        ];
    }
}