<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->json('images')->nullable()->after('emoji');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->json('images')->nullable()->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn('images');
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
