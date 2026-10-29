<?php

namespace App\Constants;

class PaymentStatus
{
    const PENDING   = 'pending';
    const PAID      = 'paid';
    const FAILED    = 'failed';
    const CANCELLED = 'cancelled';

    public static function all(): array
    {
        return [
            self::PENDING,
            self::PAID,
            self::FAILED,
            self::CANCELLED,
        ];
    }
}