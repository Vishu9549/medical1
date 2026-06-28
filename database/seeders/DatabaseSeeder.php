<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $users = [
            [
                'id' => 1,
                'name' => 'System Admin',
                'email' => 'admin@dawalo.in',
                'password' => Hash::make('admin1234'),
                'phone' => '0000000000',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Rajesh Sharma',
                'email' => 'owner1@dawalo.in',
                'password' => Hash::make('owner1234'),
                'phone' => '9876543210',
                'role' => 'shop_owner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Anil Gupta',
                'email' => 'owner2@dawalo.in',
                'password' => Hash::make('owner1234'),
                'phone' => '9123456780',
                'role' => 'shop_owner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Vikram Singh',
                'email' => 'owner3@dawalo.in',
                'password' => Hash::make('owner1234'),
                'phone' => '9988776655',
                'role' => 'shop_owner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Rohan Customer',
                'email' => 'customer@dawalo.in',
                'password' => Hash::make('customer1234'),
                'phone' => '9999999999',
                'role' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($users as $user) {
            DB::table('users')->insertOrIgnore($user);
        }

        // 2. Call Modular Seeders
        $this->call([
            SettingSeeder::class,
            MedicineSeeder::class,
            ShopSeeder::class,
            InventorySeeder::class,
        ]);

        // 3. Orders
        $orders = [
            [
                'id' => 1001,
                'shop_id' => 1,
                'status' => 'Delivered',
                'mode' => 'pickup',
                'total_price' => 50,
                'delivery_charge' => 0.00,
                'items' => json_encode([
                    ['name' => 'Paracetamol 500mg', 'price' => 25, 'quantity' => 2, 'emoji' => '🌡️']
                ]),
                'user_id' => 5,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'id' => 1002,
                'shop_id' => 1,
                'status' => 'Pending',
                'mode' => 'delivery',
                'total_price' => 38,
                'delivery_charge' => 8.00,
                'items' => json_encode([
                    ['name' => 'Dolo 650mg', 'price' => 30, 'quantity' => 1, 'emoji' => '🌡️']
                ]),
                'user_id' => 5,
                'created_at' => now()->subMinutes(30),
                'updated_at' => now()->subMinutes(30),
            ]
        ];

        foreach ($orders as $order) {
            DB::table('orders')->insertOrIgnore($order);
        }
    }
}
