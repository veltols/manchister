<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 004: Internal Report (Checklist)
        if (!Schema::hasTable('eqa_004')) {
            Schema::create('eqa_004', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('atp_id')->unique();
                $table->json('action_plan')->nullable();
                $table->json('staff_names')->nullable();
                $table->json('staff_roles')->nullable();

                // Specific fields from form
                $table->text('final_recommendation')->nullable();
                $table->text('report_sections')->nullable();
                $table->date('review_date')->nullable();

                // Flexible storage for other fields
                $table->json('details')->nullable();

                $table->timestamps();
            });
        }

        // 014: Site Inspection (Checklist)
        if (!Schema::hasTable('eqa_014')) {
            Schema::create('eqa_014', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('atp_id')->unique();

                // Flexible storage for checklist items
                $table->json('site_details')->nullable();
                $table->json('health_safety_checks')->nullable();
                $table->json('equipment_checks')->nullable();

                $table->json('details')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eqa_004');
        Schema::dropIfExists('eqa_014');
    }
};
