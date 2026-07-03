<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->string('product_id')->nullable();
            $table->string('marketer')->nullable();
            $table->text('composition')->nullable();
            $table->string('medicine_type')->nullable();
            $table->text('introduction')->nullable();
            $table->text('benefits')->nullable();
            $table->text('how_to_use')->nullable();
            $table->text('safety_advise')->nullable();
            $table->text('if_miss')->nullable();
            $table->text('packaging_detail')->nullable();
            $table->string('package')->nullable();
            $table->string('qty')->nullable();
            $table->string('product_form')->nullable();
            $table->string('prescription_required')->nullable();
            $table->text('fact_box')->nullable();
            $table->text('primary_use')->nullable();
            $table->string('storage')->nullable();
            $table->text('side_effect')->nullable();
            $table->text('alcohol_interaction')->nullable();
            $table->text('pregnancy_interaction')->nullable();
            $table->text('lactation_interaction')->nullable();
            $table->text('driving_interaction')->nullable();
            $table->text('kidney_interaction')->nullable();
            $table->text('liver_interaction')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->text('q_a')->nullable();
            $table->text('how_it_works')->nullable();
            $table->text('drug_drug_interaction')->nullable();
            $table->text('marketer_details')->nullable();
            $table->text('image_urls')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn([
                'product_id',
                'marketer',
                'composition',
                'medicine_type',
                'introduction',
                'benefits',
                'how_to_use',
                'safety_advise',
                'if_miss',
                'packaging_detail',
                'package',
                'qty',
                'product_form',
                'prescription_required',
                'fact_box',
                'primary_use',
                'storage',
                'side_effect',
                'alcohol_interaction',
                'pregnancy_interaction',
                'lactation_interaction',
                'driving_interaction',
                'kidney_interaction',
                'liver_interaction',
                'country_of_origin',
                'q_a',
                'how_it_works',
                'drug_drug_interaction',
                'marketer_details',
                'image_urls',
            ]);
        });
    }
};
