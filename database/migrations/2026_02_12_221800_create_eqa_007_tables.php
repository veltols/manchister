<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('eqa_007_areas')) {
            Schema::create('eqa_007_areas', function (Blueprint $table) {
                $table->id('record_id');
                $table->integer('atp_id');
                $table->text('a1')->nullable(); // Evidence
                $table->text('a2')->nullable(); // Met Criteria
                $table->text('a3')->nullable(); // Explanation
                $table->integer('added_by')->nullable();
                $table->dateTime('added_date')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('eqa_007_interview')) {
            Schema::create('eqa_007_interview', function (Blueprint $table) {
                $table->id('interview_id');
                $table->integer('atp_id');
                $table->string('interview_type'); // staff, iqa, train
                $table->string('staff_name')->nullable();
                $table->string('staff_role')->nullable();
                $table->text('eqa_comment')->nullable();
                $table->text('question')->nullable();
                $table->text('answer')->nullable();
                $table->integer('added_by')->nullable();
                $table->dateTime('added_date')->nullable();
                $table->timestamps();
            });
        }

        // Alter existing atps_sed_form if columns are missing
        Schema::table('atps_sed_form', function (Blueprint $table) {
            if (!Schema::hasColumn('atps_sed_form', 'submitted_date')) {
                $table->dateTime('submitted_date')->nullable();
            }
            if (!Schema::hasColumn('atps_sed_form', 'added_date')) {
                $table->dateTime('added_date')->nullable();
            }
            if (!Schema::hasColumn('atps_sed_form', 'is_submitted')) {
                $table->integer('is_submitted')->default(0);
            }
            if (!Schema::hasColumn('atps_sed_form', 'form_status')) {
                $table->string('form_status')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eqa_007_areas');
        Schema::dropIfExists('eqa_007_interview');
        
        Schema::table('atps_sed_form', function (Blueprint $table) {
            $table->dropColumn(['submitted_date', 'added_date', 'is_submitted', 'form_status']);
        });
    }
};
