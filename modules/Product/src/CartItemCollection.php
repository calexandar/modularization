<?php

namespace Modules\Product;

use Illuminate\Support\Collection;
use Modules\Product\Models\CartItem;
use Modules\Product\Models\Product;

class CartItemCollection
{
    /**
     * CartItemCollection constructor.
     *
     * @param  Collection  $items  The collection of cart items.
     */
    public function __construct(
        protected Collection $items
    ) {}

    public static function fromCheckoutData(array $data): CartItemCollection
    {
        $cartItems = collect($data)->map(function (array $productDetails) {
            return new CartItem(
                ProductDTO::fromEloquentModel(Product::find($productDetails['id'])),
                $productDetails['quantity']);
        });

        return new self($cartItems);
    }

    public function totalInCents()
    {
        return $this->items->sum(fn (CartItem $cartItem) => $cartItem->product->priceInCents * $cartItem->quantity
        );
    }

    /**
     * Get the collection of cart items.
     *
     * @return Collection The collection of cart items.
     */
    public function items(): Collection
    {
        return $this->items;
    }
}
