<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('owner_name');
            $table->string('phone');
            $table->string('area');
            $table->text('address');
            $table->decimal('rating', 3, 2)->default(5.0);
            $table->integer('reviews')->default(0);
            $table->decimal('distance_km', 5, 2)->default(0.0);
            $table->boolean('is_top')->default(false);
            $table->boolean('delivery_enabled')->default(true);
            $table->boolean('is_online')->default(true);
            $table->string('status')->default('approved'); // approved, pending, blocked
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
