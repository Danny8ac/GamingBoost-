<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'provider',
        'total_amount',
        'currency',
        'provider_ref',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}