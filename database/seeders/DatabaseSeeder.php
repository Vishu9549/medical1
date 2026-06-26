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
            DB::table('users')->insert($user);
        }

        // 2. Settings
        DB::table('settings')->insert([
            ['key' => 'comm_on', 'value' => 'true', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'comm_rate', 'value' => '2', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Medicines
        $medicines = [
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

        foreach ($medicines as $med) {
            DB::table('medicines')->insert(array_merge($med, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 4. Shops
        $shops = [
            [
                'id' => 1,
                'name' => 'Sharma Medical Store',
                'owner_name' => 'Rajesh Sharma',
                'phone' => '9876543210',
                'area' => 'Mithanpura',
                'address' => 'Near Mithanpura Chowk, Muzaffarpur',
                'rating' => 4.8,
                'reviews' => 124,
                'distance_km' => 0.4,
                'is_top' => true,
                'delivery_enabled' => true,
                'is_online' => true,
                'status' => 'approved',
                'user_id' => 2,
                'latitude' => 26.12000000,
                'longitude' => 85.36400000,
            ],
            [
                'id' => 2,
                'name' => 'Gupta Pharma',
                'owner_name' => 'Anil Gupta',
                'phone' => '9123456780',
                'area' => 'Brahmpura',
                'address' => 'Brahmpura Main Road, Muzaffarpur',
                'rating' => 4.5,
                'reviews' => 89,
                'distance_km' => 1.2,
                'is_top' => false,
                'delivery_enabled' => false,
                'is_online' => true,
                'status' => 'approved',
                'user_id' => 3,
                'latitude' => 26.12500000,
                'longitude' => 85.37000000,
            ],
            [
                'id' => 3,
                'name' => 'Singh Medical',
                'owner_name' => 'Vikram Singh',
                'phone' => '9988776655',
                'area' => 'Juran Chapra',
                'address' => 'Road No. 2, Juran Chapra, Muzaffarpur',
                'rating' => 4.7,
                'reviews' => 203,
                'distance_km' => 2.1,
                'is_top' => false,
                'delivery_enabled' => true,
                'is_online' => true,
                'status' => 'approved',
                'user_id' => 4,
                'latitude' => 26.11500000,
                'longitude' => 85.35500000,
            ],
            [
                'id' => 4,
                'name' => 'Yadav Medical',
                'owner_name' => 'Suresh Yadav',
                'phone' => '9090909090',
                'area' => 'Ramna Road',
                'address' => 'Ramna Road, Near University, Muzaffarpur',
                'rating' => 4.3,
                'reviews' => 56,
                'distance_km' => 2.8,
                'is_top' => false,
                'delivery_enabled' => true,
                'is_online' => true,
                'status' => 'blocked',
                'user_id' => null,
                'latitude' => 26.13000000,
                'longitude' => 85.36000000,
            ],
            [
                'id' => 5,
                'name' => 'Verma Pharmacy',
                'owner_name' => 'Pooja Verma',
                'phone' => '9555512345',
                'area' => 'Aghoria Bazar',
                'address' => 'Aghoria Bazar Chowk, Muzaffarpur',
                'rating' => 5.0,
                'reviews' => 0,
                'distance_km' => 3.5,
                'is_top' => false,
                'delivery_enabled' => true,
                'is_online' => true,
                'status' => 'pending',
                'user_id' => null,
                'latitude' => 26.11000000,
                'longitude' => 85.38000000,
            ]
        ];

        foreach ($shops as $shop) {
            DB::table('shops')->insert(array_merge($shop, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 5. Wallets
        $wallets = [
            ['shop_id' => 1, 'total_sales' => 4820, 'due_commission' => 45, 'credit_limit' => 100, 'status' => 'active'],
            ['shop_id' => 2, 'total_sales' => 2100, 'due_commission' => 138, 'credit_limit' => 100, 'status' => 'restricted'],
            ['shop_id' => 3, 'total_sales' => 7300, 'due_commission' => 0, 'credit_limit' => 100, 'status' => 'active'],
            ['shop_id' => 4, 'total_sales' => 1600, 'due_commission' => 32, 'credit_limit' => 100, 'status' => 'active'],
            ['shop_id' => 5, 'total_sales' => 0, 'due_commission' => 0, 'credit_limit' => 100, 'status' => 'active'],
        ];

        foreach ($wallets as $wallet) {
            DB::table('wallets')->insert(array_merge($wallet, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 6. Inventories
        $inventories = [
            // Shop 1
            ['shop_id' => 1, 'medicine_id' => 1, 'price' => 25, 'quantity' => 50],
            ['shop_id' => 1, 'medicine_id' => 6, 'price' => 30, 'quantity' => 50],
            ['shop_id' => 1, 'medicine_id' => 3, 'price' => 20, 'quantity' => 50],
            ['shop_id' => 1, 'medicine_id' => 4, 'price' => 45, 'quantity' => 50],
            ['shop_id' => 1, 'medicine_id' => 5, 'price' => 22, 'quantity' => 50],

            // Shop 2
            ['shop_id' => 2, 'medicine_id' => 2, 'price' => 90, 'quantity' => 50],
            ['shop_id' => 2, 'medicine_id' => 1, 'price' => 18, 'quantity' => 50],
            ['shop_id' => 2, 'medicine_id' => 7, 'price' => 35, 'quantity' => 50],
            ['shop_id' => 2, 'medicine_id' => 8, 'price' => 80, 'quantity' => 50],

            // Shop 3
            ['shop_id' => 3, 'medicine_id' => 3, 'price' => 22, 'quantity' => 50],
            ['shop_id' => 3, 'medicine_id' => 5, 'price' => 20, 'quantity' => 50],
            ['shop_id' => 3, 'medicine_id' => 6, 'price' => 28, 'quantity' => 50],
            ['shop_id' => 3, 'medicine_id' => 4, 'price' => 40, 'quantity' => 50],
            ['shop_id' => 3, 'medicine_id' => 1, 'price' => 15, 'quantity' => 50],

            // Shop 4
            ['shop_id' => 4, 'medicine_id' => 7, 'price' => 30, 'quantity' => 50],
            ['shop_id' => 4, 'medicine_id' => 8, 'price' => 75, 'quantity' => 50],
            ['shop_id' => 4, 'medicine_id' => 2, 'price' => 85, 'quantity' => 50],
        ];

        foreach ($inventories as $inv) {
            DB::table('inventories')->insert(array_merge($inv, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 7. Orders
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
            DB::table('orders')->insert($order);
        }
    }
}
