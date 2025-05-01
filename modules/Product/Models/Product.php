<?php

namespace Modules\Product\Models;

use Modules\Product\Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price_in_cents', 'stock'];

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }
}
