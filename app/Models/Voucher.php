<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = ['code', 'name', 'type', 'value', 'min_purchase', 'expired_at'];

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
