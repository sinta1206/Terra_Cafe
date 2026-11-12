<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'payment_gateway', 'transaction_id', 'amount', 'status', 'response_payload'];

    protected $casts = [
        'response_payload' => 'array',
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
