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
        // 017: Assessor Interview
        if (!Schema::hasTable('eqa_017')) {
            Schema::create('eqa_017', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('atp_id')->unique();
                $table->string('assessor_name')->nullable();
                $table->json('questions')->nullable();
                $table->json('answers')->nullable();
                $table->json('details')->nullable(); // Catch-all
                $table->timestamps();
            });
        }

        // 018: IQA Interview
        if (!Schema::hasTable('eqa_018')) {
            Schema::create('eqa_018', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('atp_id')->unique();
                $table->string('iqa_name')->nullable();
                $table->json('questions')->nullable();
                $table->json('answers')->nullable();
                $table->json('details')->nullable();
                $table->timestamps();
            });
        }

        // 019: Learner Interview
        if (!Schema::hasTable('eqa_019')) {
            Schema::create('eqa_019', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('atp_id')->unique();
                $table->string('learner_name')->nullable();
                $table->json('questions')->nullable();
                $table->json('answers')->nullable();
                $table->json('details')->nullable();
                $table->timestamps();
            });
        }

        // 020: Lead IQA Interview
        if (!Schema::hasTable('eqa_020')) {
            Schema::create('eqa_020', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('atp_id')->unique();
                $table->string('lead_iqa_name')->nullable();
                $table->json('questions')->nullable();
                $table->json('answers')->nullable();
                $table->json('details')->nullable();
                $table->timestamps();
            });
        }

        // 028: Live Assessment
        if (!Schema::hasTable('eqa_028')) {
            Schema::create('eqa_028', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('atp_id')->unique();
                $table->json('assessors')->nullable();
                $table->json('learners')->nullable();
                $table->json('learner_names')->nullable();
                $table->json('learner_cohorts')->nullable();
                $table->json('recommendations')->nullable();
                $table->json('actions')->nullable();
                $table->json('action_texts')->nullable();
                $table->json('action_dates')->nullable();

                $table->json('details')->nullable(); // For dynamic criteria
                $table->timestamps();
            });
        }

        // 049: Teaching Observation
        if (!Schema::hasTable('eqa_049')) {
            Schema::create('eqa_049', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('atp_id')->unique();
                $table->string('instructor_name')->nullable();
                $table->string('course_module')->nullable();
                $table->string('lesson_plan_available')->nullable();
                $table->string('materials_prepared')->nullable();
                $table->text('objectives')->nullable();

                $table->text('strengths')->nullable();
                $table->text('improvements')->nullable();
                $table->text('suggestions')->nullable();
                $table->text('feedback')->nullable();

                // Store dynamic criteria ratings/comments in a JSON column
                $table->json('criteria_data')->nullable(); // Used by FormController for extra fields

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eqa_017');
        Schema::dropIfExists('eqa_018');
        Schema::dropIfExists('eqa_019');
        Schema::dropIfExists('eqa_020');
        Schema::dropIfExists('eqa_028');
        Schema::dropIfExists('eqa_049');
    }
};
