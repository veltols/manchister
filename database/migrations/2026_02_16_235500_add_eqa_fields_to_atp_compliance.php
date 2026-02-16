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
        Schema::table('atp_compliance', function (Blueprint $table) {
            if (!Schema::hasColumn('atp_compliance', 'eqa_criteria')) {
                $table->integer('eqa_criteria')->default(100)->after('criteria_id'); // 100 is "Please Select"
            }
            if (!Schema::hasColumn('atp_compliance', 'eqa_feedback')) {
                $table->text('eqa_feedback')->nullable()->after('eqa_criteria');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atp_compliance', function (Blueprint $table) {
            $table->dropColumn(['eqa_criteria', 'eqa_feedback']);
        });
    }
};
