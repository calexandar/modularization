<?php

namespace Modules\Order\Models;

use App\Models\User;
use Modules\Payment\Payment;
use Modules\Order\Models\OrderLine;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\CartItemCollection;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Exceptions\OrderMissingOrderLinesException;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_id',
        'status',
        'payment_gateway',
        'total_in_cents',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'total_in_cents' => 'integer',
    ];

    public const PENDING = 'pending';
    public const COMPLETED = 'completed';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function lastPayment(): HasOne
    {
        return $this->payments()->one()->latest();        
    }
    public function lines(): HasMany
    {
        return $this->hasMany(OrderLine::class);
    }

    public function url(): string
    {
        return route('orders.show', $this);        
    }

    public static   function startForUser(int $userId): self
    {
        return self::make([
            'user_id' => $userId,
            'status' => self::PENDING
        ]);
    }

    /**
     * Add line items to the order from the given CartItemCollection.
     *
     * @param CartItemCollection $items Collection of cart items to be added as line items.
     * @return void
     */

    public function addLineitemsFromCartItems(CartItemCollection $items): void
    {
        foreach($items->items() as $cartItem){
            $this->lines()->push(OrderLine::make([
                'product_id' => $cartItem->product->id,
                'product_price_in_cents' => $cartItem->product->priceInCents,
                'quantity' => $cartItem->quantity
            ]));
        }
      
    }

    /**
     * Complete the order by marking its status as completed and saving all the lines.
     *
     * @throws OrderMissingOrderLinesException
     * @return void
     */
    public function fulfill(): void
    {
        if($this->lines()->isEmpty()) {
            throw new OrderMissingOrderLinesException();
        }
        
        $this->status = self::COMPLETED;

        $this->save();
        $this->lines()->saveMany($this->lines());
    }
}
