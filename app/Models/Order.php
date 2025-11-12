<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_date',
        'channel',
        'sku',
        'origin',
        'so_num',
        'cost',
        'shipping_cost',
        'total_price',
    ];

    protected $casts = [
        'order_date' => 'date',
        'cost' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];
}
