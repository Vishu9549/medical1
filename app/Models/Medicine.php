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
        'product_id',
        'marketer',
        'composition',
        'medicine_type',
        'introduction',
        'benefits',
        'how_to_use',
        'safety_advise',
        'if_miss',
        'packaging_detail',
        'package',
        'qty',
        'product_form',
        'prescription_required',
        'fact_box',
        'primary_use',
        'storage',
        'side_effect',
        'alcohol_interaction',
        'pregnancy_interaction',
        'lactation_interaction',
        'driving_interaction',
        'kidney_interaction',
        'liver_interaction',
        'country_of_origin',
        'q_a',
        'how_it_works',
        'drug_drug_interaction',
        'marketer_details',
        'image_urls',
    ];

    protected $casts = [
        'mrp' => 'float',
        'price' => 'float',
    ];

    public function getImagesAttribute()
    {
        if (!empty($this->image_urls)) {
            return array_filter(array_map('trim', explode(',', $this->image_urls)));
        }
        return [];
    }

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
