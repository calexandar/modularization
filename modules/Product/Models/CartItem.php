<?php

namespace Modules\Product\Models;

use Modules\Product\ProductDTO;

readonly class CartItem
{
    public function __construct(
        public ProductDTO $product,
        public int $quantity
    ) {}
}
