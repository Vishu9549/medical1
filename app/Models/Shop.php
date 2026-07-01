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
        'opens_at',
        'closes_at',
    ];

    public function isOpen()
    {
        date_default_timezone_set('Asia/Kolkata');
        $currentTime = date('H:i');
        $opensAt = $this->opens_at ?? '09:00';
        $closesAt = $this->closes_at ?? '21:00';

        if ($opensAt <= $closesAt) {
            return ($currentTime >= $opensAt && $currentTime <= $closesAt);
        } else {
            return ($currentTime >= $opensAt || $currentTime <= $closesAt);
        }
    }

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
