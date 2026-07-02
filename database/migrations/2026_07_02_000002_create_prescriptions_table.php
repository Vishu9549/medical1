<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('shop_id')->nullable()->constrained()->onDelete('set null');
            $table->string('image_path');
            $table->string('patient_name');
            $table->integer('patient_age')->nullable();
            $table->string('patient_phone');
            $table->text('delivery_address');
            $table->text('notes')->nullable();
            $table->string('status')->default('Pending'); // Pending, Accepted, Out for Delivery, Delivered, Cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
