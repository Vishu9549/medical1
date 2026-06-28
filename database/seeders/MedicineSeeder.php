<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $prefixes = ['Al', 'Ben', 'Cal', 'Dol', 'Ery', 'Flu', 'Glip', 'Hep', 'Ibu', 'Juv', 'Kof', 'Lip', 'Met', 'Neo', 'Ome', 'Pan', 'Qin', 'Ros', 'Sita', 'Tel', 'Uni', 'Val', 'Zol'];
        $middles = ['a', 'o', 'i', 'e', 'u', 'as', 'in', 'ex', 'or', 'ap', 'ip', 'op', 'at'];
        $suffixes = ['stat', 'nac', 'zole', 'cillin', 'pril', 'sartan', 'olol', 'press', 'phage', 'formin', 'tidine', 'fen', 'cef', 'mox', 'dopa', 'zine'];
        $dosages = ['5mg', '10mg', '25mg', '50mg', '100mg', '250mg', '500mg', '650mg', '1g'];
        
        $categoriesData = [
            'Fever' => '🌡️',
            'Antibiotic' => '💊',
            'Allergy' => '🤧',
            'Acidity' => '🔵',
            'Pain' => '🩹',
            'Diabetes' => '💉',
            'Heart' => '❤️',
            'Supplement' => '🍊',
            'Skin' => '🧴',
            'Eye' => '👁️',
            'Dental' => '🦷'
        ];

        $medsToInsert = [];
        $uniqueNames = [];
        $counter = 1;

        $coreMeds = [
            ['id' => 1, 'name' => 'Paracetamol 500mg', 'category' => 'Fever', 'emoji' => '🌡️', 'mrp' => 32, 'price' => 25],
            ['id' => 2, 'name' => 'Azithromycin 500mg', 'category' => 'Antibiotic', 'emoji' => '💊', 'mrp' => 120, 'price' => 95],
            ['id' => 3, 'name' => 'Cetirizine 10mg', 'category' => 'Allergy', 'emoji' => '🤧', 'mrp' => 28, 'price' => 22],
            ['id' => 4, 'name' => 'Omeprazole 20mg', 'category' => 'Acidity', 'emoji' => '🔵', 'mrp' => 45, 'price' => 36],
            ['id' => 5, 'name' => 'Ibuprofen 400mg', 'category' => 'Pain', 'emoji' => '🩹', 'mrp' => 38, 'price' => 30],
            ['id' => 6, 'name' => 'Dolo 650mg', 'category' => 'Fever', 'emoji' => '🌡️', 'mrp' => 30, 'price' => 24],
            ['id' => 7, 'name' => 'Metformin 500mg', 'category' => 'Diabetes', 'emoji' => '💉', 'mrp' => 55, 'price' => 42],
            ['id' => 8, 'name' => 'Amoxicillin 250mg', 'category' => 'Antibiotic', 'emoji' => '💊', 'mrp' => 85, 'price' => 68],
            ['id' => 9, 'name' => 'Pantoprazole 40mg', 'category' => 'Acidity', 'emoji' => '🔵', 'mrp' => 60, 'price' => 48],
            ['id' => 10, 'name' => 'Vitamin C 500mg', 'category' => 'Supplement', 'emoji' => '🍊', 'mrp' => 40, 'price' => 32],
            ['id' => 11, 'name' => 'Atorvastatin 10mg', 'category' => 'Heart', 'emoji' => '❤️', 'mrp' => 95, 'price' => 76],
            ['id' => 12, 'name' => 'Metronidazole 400mg', 'category' => 'Antibiotic', 'emoji' => '💊', 'mrp' => 42, 'price' => 34],
        ];

        foreach ($coreMeds as $m) {
            $uniqueNames[strtolower($m['name'])] = true;
            $medsToInsert[] = array_merge($m, ['created_at' => now(), 'updated_at' => now()]);
            $counter++;
        }

        while ($counter <= 5000) {
            $cat = array_rand($categoriesData);
            $emoji = $categoriesData[$cat];
            
            $name = $prefixes[array_rand($prefixes)] 
                  . $middles[array_rand($middles)] 
                  . $suffixes[array_rand($suffixes)] 
                  . ' ' 
                  . $dosages[array_rand($dosages)];

            if (!isset($uniqueNames[strtolower($name)])) {
                $uniqueNames[strtolower($name)] = true;
                $mrp = rand(15, 500);
                $price = round($mrp * (rand(70, 95) / 100));
                
                $medsToInsert[] = [
                    'id' => $counter,
                    'name' => $name,
                    'category' => $cat,
                    'emoji' => $emoji,
                    'mrp' => $mrp,
                    'price' => $price,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $counter++;
            }
        }

        foreach (array_chunk($medsToInsert, 500) as $chunk) {
            DB::table('medicines')->insertOrIgnore($chunk);
        }
    }
}
