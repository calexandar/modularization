<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 
        'product_id', 
        'product_price_in_cents',
        'quantity'
    ];

    protected $casts = [
        'order_id' => 'integer',
        'product_id' => 'integer',
        'product_price_in_cents' => 'integer',
        'quantity' => 'integer'
    ];
}
