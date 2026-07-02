<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->decimal('delivery_radius_km', 5, 2)->default(10.00);
            $table->string('delivery_charge_type')->default('dynamic'); // fixed, dynamic
            $table->decimal('delivery_charge_fixed', 8, 2)->default(20.00);
            $table->decimal('delivery_charge_per_km', 8, 2)->default(8.00);
            $table->decimal('offer_min_bill', 8, 2)->default(0.00);
            $table->decimal('offer_discount_pct', 5, 2)->default(0.00);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('discount_amount', 8, 2)->default(0.00);
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_radius_km',
                'delivery_charge_type',
                'delivery_charge_fixed',
                'delivery_charge_per_km',
                'offer_min_bill',
                'offer_discount_pct',
            ]);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('discount_amount');
        });
    }
};
