<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add temporary nullable columns
        Schema::table('m_strategic_plans', function (Blueprint $table) {
            $table->date('temp_from')->nullable()->after('plan_from');
            $table->date('temp_to')->nullable()->after('plan_to');
        });

        // 2. Migrate existing data
        DB::statement("UPDATE m_strategic_plans SET temp_from = CONCAT(plan_from, '-01-01') WHERE plan_from IS NOT NULL AND plan_from > 1900");
        DB::statement("UPDATE m_strategic_plans SET temp_to = CONCAT(plan_to, '-12-31') WHERE plan_to IS NOT NULL AND plan_to > 1900");

        // Fill any nulls with default dates just in case
        DB::statement("UPDATE m_strategic_plans SET temp_from = '2000-01-01' WHERE temp_from IS NULL");
        DB::statement("UPDATE m_strategic_plans SET temp_to = '2000-12-31' WHERE temp_to IS NULL");

        // 3. Drop old columns
        Schema::table('m_strategic_plans', function (Blueprint $table) {
            $table->dropColumn(['plan_from', 'plan_to']);
        });

        // 4. Rename temp columns to original names
        Schema::table('m_strategic_plans', function (Blueprint $table) {
            $table->renameColumn('temp_from', 'plan_from');
            $table->renameColumn('temp_to', 'plan_to');
        });

        // 5. Change columns to NOT NULL
        Schema::table('m_strategic_plans', function (Blueprint $table) {
            $table->date('plan_from')->nullable(false)->change();
            $table->date('plan_to')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Add temporary nullable columns
        Schema::table('m_strategic_plans', function (Blueprint $table) {
            $table->integer('temp_from')->nullable()->after('plan_from');
            $table->integer('temp_to')->nullable()->after('plan_to');
        });

        // 2. Extract year from DATE
        DB::statement("UPDATE m_strategic_plans SET temp_from = YEAR(plan_from) WHERE plan_from IS NOT NULL");
        DB::statement("UPDATE m_strategic_plans SET temp_to = YEAR(plan_to) WHERE plan_to IS NOT NULL");

        // Fill any nulls with default years just in case
        DB::statement("UPDATE m_strategic_plans SET temp_from = 2000 WHERE temp_from IS NULL");
        DB::statement("UPDATE m_strategic_plans SET temp_to = 2000 WHERE temp_to IS NULL");

        // 3. Drop DATE columns
        Schema::table('m_strategic_plans', function (Blueprint $table) {
            $table->dropColumn(['plan_from', 'plan_to']);
        });

        // 4. Rename temp columns to original names
        Schema::table('m_strategic_plans', function (Blueprint $table) {
            $table->renameColumn('temp_from', 'plan_from');
            $table->renameColumn('temp_to', 'plan_to');
        });

        // 5. Change columns to NOT NULL
        Schema::table('m_strategic_plans', function (Blueprint $table) {
            $table->integer('plan_from')->nullable(false)->change();
            $table->integer('plan_to')->nullable(false)->change();
        });
    }
};
