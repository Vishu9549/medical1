<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'shop_id',
        'status',
        'mode',
        'total_price',
        'delivery_charge',
        'delivery_address',
        'items',
        'user_id',
        'discount_amount',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'total_price' => 'float',
        'delivery_charge' => 'float',
        'discount_amount' => 'float',
        'items' => 'array',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
