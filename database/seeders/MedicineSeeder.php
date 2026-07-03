<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign keys check to cleanly truncate and re-seed
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('medicines')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $csvPath = database_path('seeders/medicines.csv');
        if (!file_exists($csvPath)) {
            $this->command->error("CSV file not found at: $csvPath");
            return;
        }

        $file = fopen($csvPath, 'r');
        $headers = fgetcsv($file); // Read headers

        // Map header indices to keys
        $headerMap = array_flip($headers);

        $categoriesEmojis = [
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
            'Dental' => '🦷',
            'General' => '📦'
        ];

        $medsToInsert = [];
        $uniqueNames = [];
        $idCounter = 1;

        while (($row = fgetcsv($file)) !== false) {
            // Helper to get column safely
            $getCol = function($name) use ($row, $headerMap) {
                $idx = $headerMap[$name] ?? null;
                return ($idx !== null && isset($row[$idx])) ? trim($row[$idx]) : '';
            };

            $name = $getCol('Product Name');
            if (empty($name)) {
                continue;
            }

            // Skip duplicate names to prevent catalog pollution
            $nameLower = strtolower($name);
            if (isset($uniqueNames[$nameLower])) {
                continue;
            }
            $uniqueNames[$nameLower] = true;

            $composition = $getCol('Composition');
            $primaryUse = $getCol('primary_use');
            $prodForm = strtolower($getCol('Product Form'));
            $mrp = (float) $getCol('MRP');
            if ($mrp <= 0) {
                $mrp = 99.00;
            }
            // Selling price is 20% off MRP by default
            $price = round($mrp * 0.8, 2);

            // Determine category
            $category = 'General';
            $compLower = strtolower($composition);
            $useLower = strtolower($primaryUse);

            if (strpos($compLower, 'paracetamol') !== false || strpos($compLower, 'acetaminophen') !== false) {
                $category = 'Fever';
            } elseif (strpos($compLower, 'aceclofenac') !== false || strpos($compLower, 'ibuprofen') !== false || strpos($compLower, 'diclofenac') !== false || strpos($compLower, 'tramadol') !== false || strpos($compLower, 'nimesulide') !== false || strpos($useLower, 'pain') !== false) {
                $category = 'Pain';
            } elseif (strpos($compLower, 'amoxicillin') !== false || strpos($compLower, 'azithromycin') !== false || strpos($compLower, 'cefi') !== false || strpos($compLower, 'cloxacillin') !== false || strpos($compLower, 'floxacin') !== false || strpos($compLower, 'clavulanate') !== false) {
                $category = 'Antibiotic';
            } elseif (strpos($compLower, 'cetirizine') !== false || strpos($compLower, 'levocetirizine') !== false || strpos($compLower, 'loratadine') !== false || strpos($compLower, 'montelukast') !== false || strpos($compLower, 'fexofenadine') !== false) {
                $category = 'Allergy';
            } elseif (strpos($compLower, 'pantoprazole') !== false || strpos($compLower, 'omeprazole') !== false || strpos($compLower, 'rabeprazole') !== false || strpos($compLower, 'ranitidine') !== false || strpos($compLower, 'domperidone') !== false) {
                $category = 'Acidity';
            } elseif (strpos($compLower, 'metformin') !== false || strpos($compLower, 'glimepiride') !== false || strpos($compLower, 'gliclazide') !== false || strpos($compLower, 'pioglitazone') !== false || strpos($compLower, 'insulin') !== false) {
                $category = 'Diabetes';
            } elseif (strpos($compLower, 'atorvastatin') !== false || strpos($compLower, 'telmisartan') !== false || strpos($compLower, 'amlodipine') !== false || strpos($compLower, 'losartan') !== false || strpos($compLower, 'metoprolol') !== false || strpos($compLower, 'rosuvastatin') !== false) {
                $category = 'Heart';
            } elseif (strpos($compLower, 'vitamin') !== false || strpos($compLower, 'calcium') !== false || strpos($compLower, 'zinc') !== false || strpos($compLower, 'multivitamin') !== false || strpos($compLower, 'iron') !== false) {
                $category = 'Supplement';
            } elseif (strpos($prodForm, 'cream') !== false || strpos($prodForm, 'gel') !== false || strpos($prodForm, 'ointment') !== false) {
                $category = 'Skin';
            } elseif (strpos($prodForm, 'eye drop') !== false || strpos($prodForm, 'eye/ear') !== false) {
                $category = 'Eye';
            }

            $emoji = $categoriesEmojis[$category] ?? '📦';

            $medsToInsert[] = [
                'id' => $idCounter,
                'name' => $name,
                'category' => $category,
                'emoji' => $emoji,
                'mrp' => $mrp,
                'price' => $price,
                'product_id' => $getCol('Product ID'),
                'marketer' => $getCol('Marketer'),
                'composition' => $composition,
                'medicine_type' => $getCol('medicine_type'),
                'introduction' => $getCol('Introduction'),
                'benefits' => $getCol('Benefits'),
                'how_to_use' => $getCol('how_to_use'),
                'safety_advise' => $getCol('safety_advise'),
                'if_miss' => $getCol('if_miss'),
                'packaging_detail' => $getCol('Packaging Detail'),
                'package' => $getCol('Package'),
                'qty' => $getCol('Qty'),
                'product_form' => $getCol('Product Form'),
                'prescription_required' => $getCol('prescription_required'),
                'fact_box' => $getCol('Fact_Box'),
                'primary_use' => $primaryUse,
                'storage' => $getCol('storage'),
                'side_effect' => $getCol('side_effect'),
                'alcohol_interaction' => $getCol('alcoholInteraction'),
                'pregnancy_interaction' => $getCol('pregnancyInteraction'),
                'lactation_interaction' => $getCol('lactationInteraction'),
                'driving_interaction' => $getCol('drivingInteraction'),
                'kidney_interaction' => $getCol('kidneyInteraction'),
                'liver_interaction' => $getCol('liverInteraction'),
                'country_of_origin' => $getCol('country_of_origin'),
                'q_a' => $getCol('Q_A'),
                'how_it_works' => $getCol('How it works'),
                'drug_drug_interaction' => $getCol('drug-drug Interaction'),
                'marketer_details' => $getCol('Marketer details'),
                'image_urls' => $getCol('Image_Urls'),
                'created_at' => now(),
                'updated_at' => now()
            ];

            $idCounter++;
        }
        fclose($file);

        // Bulk insert in chunks of 200 items to prevent memory limits
        foreach (array_chunk($medsToInsert, 200) as $chunk) {
            DB::table('medicines')->insert($chunk);
        }

        $this->command->info("Successfully seeded " . count($medsToInsert) . " detailed medicines from CSV!");
    }
}
