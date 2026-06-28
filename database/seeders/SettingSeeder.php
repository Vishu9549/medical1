<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('settings')->insertOrIgnore([
            ['key' => 'comm_on', 'value' => 'true', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'comm_rate', 'value' => '2', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
