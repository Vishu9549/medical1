<?php
// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$med = DB::table('medicines')->where('id', 148)->first();
if ($med) {
    echo "ID: " . $med->id . "\n";
    echo "Name: " . $med->name . "\n";
    echo "Image URLs: " . $med->image_urls . "\n";
} else {
    echo "Medicine not found\n";
}
