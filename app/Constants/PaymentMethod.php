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

    public static function label(string $metode): string
    {
        return $metode === self::QRIS ? 'QRIS' : 'Transfer bank';
    }
}