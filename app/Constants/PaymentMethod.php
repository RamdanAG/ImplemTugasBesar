<?php

namespace App\Constants;

class PaymentMethod
{
    const QRIS          = 'qris';
    const BANK_TRANSFER = 'bank_transfer';

    public static function all(): array
    {
        return [self::QRIS, self::BANK_TRANSFER];
    }
}