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
        if (!Schema::hasTable('atps_list_le')) {
            Schema::create('atps_list_le', function (Blueprint $table) {
                $table->increments('le_id');
                $table->integer('atp_id');
                $table->integer('qualification_id');
                $table->dateTime('submission_date')->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('learners_no')->nullable();
                $table->string('cohort')->nullable();
            });
        }

        if (!Schema::hasTable('atps_learner_enrolled')) {
            Schema::create('atps_learner_enrolled', function (Blueprint $table) {
                $table->increments('record_id');
                $table->integer('atp_id');
                for ($i = 1; $i <= 9; $i++) {
                    $table->string("le$i")->nullable();
                }
            });
        }

        if (!Schema::hasTable('atp_compliance')) {
            Schema::create('atp_compliance', function (Blueprint $table) {
                $table->increments('record_id');
                $table->integer('atp_id');
                $table->integer('main_id');
                $table->integer('answer')->default(0);
                $table->text('attachment')->nullable();
            });
        }

        if (!Schema::hasTable('quality_standard_main')) {
            Schema::create('quality_standard_main', function (Blueprint $table) {
                $table->increments('main_id');
                $table->string('main_title');
                $table->string('main_icon')->nullable();
            });
        }

        if (!Schema::hasTable('atps_sed_form')) {
            Schema::create('atps_sed_form', function (Blueprint $table) {
                $table->increments('sed_id');
                $table->integer('atp_id');
                $table->string('sed_1')->nullable(); // Author
                $table->string('sed_2')->nullable(); // Role
                $table->date('sed_3')->nullable();   // Date
                $table->text('sed_4')->nullable();   // Overview
                $table->text('sed_6')->nullable();   // Background
                $table->text('sed_8')->nullable();   // Methodology
                $table->text('sed_10')->nullable();  // Aims
                $table->text('sed_12')->nullable();  // Delivery
                $table->text('sed_14')->nullable();  // Future
            });
        }

        if (!Schema::hasTable('atps_eqa_details')) {
            Schema::create('atps_eqa_details', function (Blueprint $table) {
                $table->increments('eqa_id');
                $table->integer('atp_id');
                $table->date('eqa_visit_date')->nullable();
            });
        }

        if (!Schema::hasTable('atps_list_locations')) {
            Schema::create('atps_list_locations', function (Blueprint $table) {
                $table->increments('location_id');
                $table->integer('atp_id');
                $table->string('location_name');
                $table->integer('classrooms_count')->default(0);
                $table->string('floor_map')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atps_list_le');
        Schema::dropIfExists('atps_learner_enrolled');
        Schema::dropIfExists('atp_compliance');
        Schema::dropIfExists('quality_standard_main');
        Schema::dropIfExists('atps_sed_form');
        Schema::dropIfExists('atps_eqa_details');
        Schema::dropIfExists('atps_list_locations');
    }
};
