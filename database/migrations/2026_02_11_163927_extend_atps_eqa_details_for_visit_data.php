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
        Schema::table('atps_eqa_details', function (Blueprint $table) {
            // Visit Planner (008) data
            if (!Schema::hasColumn('atps_eqa_details', 'visit_type')) {
                $table->string('visit_type')->nullable()->after('eqa_visit_date');
            }
            if (!Schema::hasColumn('atps_eqa_details', 'activity')) {
                $table->string('activity')->nullable()->after('visit_type');
            }
            if (!Schema::hasColumn('atps_eqa_details', 'visit_length')) {
                $table->string('visit_length')->nullable()->after('activity');
            }
            if (!Schema::hasColumn('atps_eqa_details', 'visit_scope')) {
                $table->text('visit_scope')->nullable()->after('visit_length');
            }
            if (!Schema::hasColumn('atps_eqa_details', 'visit_agenda')) {
                $table->text('visit_agenda')->nullable()->after('visit_scope');
            }
            if (!Schema::hasColumn('atps_eqa_details', 'visit_comment')) {
                $table->text('visit_comment')->nullable()->after('visit_agenda');
            }

            // Accreditation Report (003) data
            if (!Schema::hasColumn('atps_eqa_details', 'recommendation')) {
                $table->string('recommendation')->nullable()->after('visit_comment');
            }
            if (!Schema::hasColumn('atps_eqa_details', 'accreditation_date')) {
                $table->date('accreditation_date')->nullable()->after('recommendation');
            }
            if (!Schema::hasColumn('atps_eqa_details', 'general_comments')) {
                $table->text('general_comments')->nullable()->after('accreditation_date');
            }
            if (!Schema::hasColumn('atps_eqa_details', 'action_plan')) {
                $table->text('action_plan')->nullable()->after('general_comments');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atps_eqa_details', function (Blueprint $table) {
            $table->dropColumn([
                'visit_type',
                'activity',
                'visit_length',
                'visit_scope',
                'visit_agenda',
                'visit_comment',
                'recommendation',
                'accreditation_date',
                'general_comments',
                'action_plan'
            ]);
        });
    }
};
