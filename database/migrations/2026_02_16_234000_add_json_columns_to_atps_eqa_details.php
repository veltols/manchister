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
            if (!Schema::hasColumn('atps_eqa_details', 'details')) {
                $table->json('details')->nullable()->after('action_plan');
            }
            if (!Schema::hasColumn('atps_eqa_details', 'criteria_data')) {
                $table->json('criteria_data')->nullable()->after('details');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atps_eqa_details', function (Blueprint $table) {
            $table->dropColumn(['details', 'criteria_data']);
        });
    }
};
