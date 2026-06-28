<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'category',
        'emoji',
        'mrp',
        'price',
        'images',
    ];

    protected $casts = [
        'mrp' => 'float',
        'price' => 'float',
        'images' => 'array',
    ];

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}
