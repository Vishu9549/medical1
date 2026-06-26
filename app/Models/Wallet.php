<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    protected $fillable = [
        'shop_id',
        'total_sales',
        'due_commission',
        'credit_limit',
        'status',
    ];

    protected $casts = [
        'total_sales' => 'float',
        'due_commission' => 'float',
        'credit_limit' => 'float',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
