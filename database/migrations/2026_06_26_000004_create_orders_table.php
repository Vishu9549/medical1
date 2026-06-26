<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('Pending'); // Pending, Accepted, Delivered, Cancelled
            $table->string('mode')->default('pickup'); // pickup, delivery
            $table->decimal('total_price', 10, 2);
            $table->decimal('delivery_charge', 10, 2)->default(0.00);
            $table->json('items'); // JSON representation of medicines ordered
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
