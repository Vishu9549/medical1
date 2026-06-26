<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shop extends Model
{
    protected $fillable = [
        'name',
        'owner_name',
        'phone',
        'area',
        'address',
        'rating',
        'reviews',
        'distance_km',
        'is_top',
        'delivery_enabled',
        'is_online',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'rating' => 'float',
        'reviews' => 'integer',
        'distance_km' => 'float',
        'is_top' => 'boolean',
        'delivery_enabled' => 'boolean',
        'is_online' => 'boolean',
    ];

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }
}
