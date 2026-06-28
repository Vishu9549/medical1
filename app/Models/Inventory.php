<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $fillable = [
        'shop_id',
        'medicine_id',
        'name',
        'price',
        'quantity',
        'images',
    ];

    protected $casts = [
        'price' => 'float',
        'quantity' => 'integer',
        'images' => 'array',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}
