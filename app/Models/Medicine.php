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
            // Split by pipe '|', comma ',', or whitespace
            $urls = preg_split('/[\s,\|]+/', $this->image_urls);
            return array_filter(array_map('trim', $urls));
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

    public function getParsedSafetyAdviseAttribute()
    {
        if (empty($this->safety_advise)) {
            return [];
        }

        $blocks = explode('|', $this->safety_advise);
        $result = [];

        foreach ($blocks as $block) {
            $block = trim($block);
            if (empty($block)) continue;

            $block = ltrim($block, '- ');

            $parts = explode(':', $block, 2);
            if (count($parts) < 2) continue;

            $title = trim($parts[0]);
            $remainder = trim($parts[1]);

            $descParts = preg_split('/<p\s*\/?>/i', $remainder, 2);
            $status = trim(strip_tags($descParts[0] ?? ''));
            $desc = trim(strip_tags($descParts[1] ?? ''));

            $result[] = [
                'title' => $title,
                'status' => $status,
                'description' => $desc,
                'icon' => $this->getInteractionIcon($title)
            ];
        }

        return $result;
    }

    private function getInteractionIcon($title)
    {
        $title = strtolower($title);
        if (strpos($title, 'alcohol') !== false) return '🍺';
        if (strpos($title, 'pregnancy') !== false) return '🤰';
        if (strpos($title, 'breast') !== false || strpos($title, 'lactation') !== false) return '🤱';
        if (strpos($title, 'driving') !== false) return '🚗';
        if (strpos($title, 'kidney') !== false) return '🔬';
        if (strpos($title, 'liver') !== false) return '🧪';
        return '🛡️';
    }

    public function getParsedQaAttribute()
    {
        if (empty($this->q_a)) {
            return [];
        }

        $blocks = explode('|', $this->q_a);
        $result = [];

        foreach ($blocks as $block) {
            $block = trim($block);
            if (empty($block)) continue;

            $parts = explode(':::', $block, 2);
            if (count($parts) < 2) continue;

            $result[] = [
                'question' => trim($parts[0]),
                'answer' => trim($parts[1])
            ];
        }

        return $result;
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}
