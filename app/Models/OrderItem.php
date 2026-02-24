<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'boost_id',
        'qty',
        'unit_price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}