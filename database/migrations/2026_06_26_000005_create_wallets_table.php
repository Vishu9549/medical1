<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->decimal('total_sales', 12, 2)->default(0.00);
            $table->decimal('due_commission', 12, 2)->default(0.00);
            $table->decimal('credit_limit', 12, 2)->default(100.00);
            $table->string('status')->default('active'); // active, restricted
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
