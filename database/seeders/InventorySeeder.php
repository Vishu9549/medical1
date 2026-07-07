<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $allShops = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $medicines = DB::table('medicines')->select('id', 'price')->limit(15)->get();

        $invToInsert = [];
        foreach ($allShops as $shopId) {
            foreach ($medicines as $med) {
                $variance = rand(-3, 5);
                $price = max(5, $med->price + $variance);
                $quantity = rand(10, 150);

                $invToInsert[] = [
                    'shop_id' => $shopId,
                    'medicine_id' => $med->id,
                    'name' => null,
                    'price' => $price,
                    'quantity' => $quantity,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        foreach (array_chunk($invToInsert, 500) as $chunk) {
            DB::table('inventories')->insertOrIgnore($chunk);
        }
    }
}
