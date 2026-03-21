<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship to OrderItem.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Accessor: This "fakes" the total_amount column for the rest of your app.
     * It calculates the total from the snapshots in order_items.
     */
    protected function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->orderItems->sum(fn($item) => $item->price * $item->quantity),
        );
    }
}