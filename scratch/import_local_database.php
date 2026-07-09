<?php
// Bootstrap Laravel locally
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$csvPath = __DIR__ . '/../database/seeders/medicines_merged.csv';
if (!file_exists($csvPath)) {
    // Check if zip exists and extract it first
    $zipPath = __DIR__ . '/../database/seeders/medicines_merged.zip';
    if (file_exists($zipPath)) {
        echo "CSV not found but ZIP found. Extracting ZIP...\n";
        $zip = new ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo(__DIR__ . '/../database/seeders/');
            $zip->close();
            echo "Extracted CSV successfully.\n";
        } else {
            exit("Failed to extract ZIP.\n");
        }
    } else {
        exit("Neither CSV nor ZIP found at seeder directory.\n");
    }
}

echo "CSV file found at $csvPath. Starting truncate of local database tables...\n";

DB::statement('SET FOREIGN_KEY_CHECKS=0;');
DB::table('inventories')->truncate();
DB::table('medicines')->truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "Truncate complete. Importing medicines into 'medicines' table...\n";

$file = fopen($csvPath, 'r');
$headers = fgetcsv($file);
$headerMap = array_flip($headers);

$chunk = [];
$count = 0;
$totalImported = 0;

while (($row = fgetcsv($file)) !== false) {
    $getCol = function($name) use ($row, $headerMap) {
        $idx = $headerMap[$name] ?? null;
        return ($idx !== null && isset($row[$idx])) ? trim($row[$idx]) : '';
    };
    
    $mrp = (float) $getCol('mrp');
    $price = (float) $getCol('price');
    
    $chunk[] = [
        'name' => $getCol('name'),
        'category' => $getCol('category'),
        'emoji' => $getCol('emoji'),
        'mrp' => $mrp,
        'price' => $price,
        'product_id' => $getCol('product_id'),
        'marketer' => $getCol('marketer'),
        'composition' => $getCol('composition'),
        'product_form' => $getCol('product_form'),
        'image_urls' => $getCol('image_urls'),
        'created_at' => now(),
        'updated_at' => now()
    ];
    
    $count++;
    if ($count >= 2000) { // Large chunks for fast local SQLite/MySQL inserts
        DB::table('medicines')->insert($chunk);
        $totalImported += $count;
        echo "Imported $totalImported medicines...\n";
        $chunk = [];
        $count = 0;
    }
}

if (!empty($chunk)) {
    DB::table('medicines')->insert($chunk);
    $totalImported += $count;
    echo "Imported $totalImported medicines...\n";
}
fclose($file);

echo "Finished importing $totalImported medicines locally!\n";

// Populate local shops inventories
$shops = DB::table('shops')->get(['id']);
$medicines = DB::table('medicines')->get(['id', 'price']);

echo "Found " . count($shops) . " shops locally. Populating inventories...\n";

foreach ($shops as $shop) {
    echo "Populating local Shop ID: {$shop->id}...\n";
    $invChunk = [];
    $invCount = 0;
    $totalInv = 0;
    
    foreach ($medicines as $med) {
        $invChunk[] = [
            'shop_id' => $shop->id,
            'medicine_id' => $med->id,
            'price' => $med->price,
            'quantity' => 100,
            'created_at' => now(),
            'updated_at' => now()
        ];
        $invCount++;
        
        if ($invCount >= 2000) {
            DB::table('inventories')->insert($invChunk);
            $totalInv += $invCount;
            $invChunk = [];
            $invCount = 0;
        }
    }
    
    if (!empty($invChunk)) {
        DB::table('inventories')->insert($invChunk);
        $totalInv += $invCount;
    }
    echo "Populated {$totalInv} inventory items for local Shop ID: {$shop->id}\n";
}

echo "Local database sync complete successfully!\n";
