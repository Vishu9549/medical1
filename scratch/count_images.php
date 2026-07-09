<?php
// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$total = DB::table('medicines')->count();
$withImages = DB::table('medicines')
    ->whereNotNull('image_urls')
    ->where('image_urls', '!=', '')
    ->where('image_urls', '!=', '[]')
    ->count();

echo "Total Medicines: $total\n";
echo "Medicines with Image URLs: $withImages\n";
