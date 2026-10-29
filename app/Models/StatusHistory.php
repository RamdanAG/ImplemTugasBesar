<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    // tabel ini cuma punya created_at, tidak ada updated_at
    const UPDATED_AT = null;

    protected $fillable = ['order_id', 'old_status', 'new_status', 'note'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}