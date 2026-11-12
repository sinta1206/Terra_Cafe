<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'invoice_number', 'user_id', 'voucher_id', 'subtotal', 'discount', 'total_amount', 'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function voucher() {
        return $this->belongsTo(Voucher::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }
}
