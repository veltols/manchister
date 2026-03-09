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
        Schema::table('atps_eqa_details', function (Blueprint $table) {
            if (!Schema::hasColumn('atps_eqa_details', 'assigned_to')) {
                $table->integer('assigned_to')->nullable()->after('atp_id');
            }
            if (!Schema::hasColumn('atps_eqa_details', 'form_status')) {
                $table->string('form_status')->nullable()->after('assigned_to');
            }
            if (!Schema::hasColumn('atps_eqa_details', 'added_date')) {
                $table->dateTime('added_date')->nullable();
            }
            if (!Schema::hasColumn('atps_eqa_details', 'submitted_date')) {
                $table->dateTime('submitted_date')->nullable();
            }
            if (!Schema::hasColumn('atps_eqa_details', 'is_submitted')) {
                $table->tinyInteger('is_submitted')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atps_eqa_details', function (Blueprint $table) {
            $table->dropColumn(['assigned_to', 'form_status', 'added_date', 'submitted_date', 'is_submitted']);
        });
    }
};
