<?php

namespace App\Models;

use App\Constants\OrderStatus;
use App\Constants\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'payment_method',
        'amount', 'status', 'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // satu-satunya aturan "penjualan berhasil": pembayaran paid dan pesanan tidak dibatalkan
    public function scopePenjualan($query)
    {
        return $query->where('status', PaymentStatus::PAID)
            ->whereHas('order', function ($q) {
                $q->where('status', '!=', OrderStatus::CANCELLED);
            });
    }
}