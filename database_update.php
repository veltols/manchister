<?php
use Illuminate\Support\Facades\DB;

DB::statement("ALTER TABLE m_strategic_plans ADD COLUMN temp_from DATE");
DB::statement("UPDATE m_strategic_plans SET temp_from = CONCAT(plan_from, '-01-01') WHERE plan_from IS NOT NULL AND plan_from > 1900");
DB::statement("ALTER TABLE m_strategic_plans DROP COLUMN plan_from");
DB::statement("ALTER TABLE m_strategic_plans CHANGE temp_from plan_from DATE");

DB::statement("ALTER TABLE m_strategic_plans ADD COLUMN temp_to DATE");
DB::statement("UPDATE m_strategic_plans SET temp_to = CONCAT(plan_to, '-12-31') WHERE plan_to IS NOT NULL AND plan_to > 1900");
DB::statement("ALTER TABLE m_strategic_plans DROP COLUMN plan_to");
DB::statement("ALTER TABLE m_strategic_plans CHANGE temp_to plan_to DATE");

echo "Done\n";
