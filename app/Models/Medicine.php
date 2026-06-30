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

    public function getGenericNameAttribute()
    {
        if (preg_match('/([A-Za-z\s]+)/', $this->name, $matches)) {
            return trim($matches[1]) . " Active Compound";
        }
        return $this->category . " Formula";
    }

    public function getStrengthAttribute()
    {
        if (preg_match('/(\d+(?:\.\d+)?\s*(?:mg|ml|g|mcg|tab|caps))/i', $this->name, $matches)) {
            return $matches[1];
        }
        return "500 mg";
    }

    public function getCompanyAttribute()
    {
        if (preg_match('/\(([^)]+)\)/', $this->name, $matches)) {
            return $matches[1];
        }
        $companies = ['Cipla Ltd', 'Abbott India', 'Sun Pharma', 'Alkem Laboratories', 'Mankind Pharma', 'Lupin Ltd'];
        return $companies[$this->id % count($companies)];
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}
