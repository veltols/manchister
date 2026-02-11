<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('atp_compliance', function (Blueprint $table) {
            if (!Schema::hasColumn('atp_compliance', 'feedback')) {
                $table->text('feedback')->nullable()->after('answer');
            }
            if (!Schema::hasColumn('atp_compliance', 'criteria_id')) {
                $table->integer('criteria_id')->nullable()->after('feedback'); // Link to specific criteria within a main standard
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atp_compliance', function (Blueprint $table) {
            $table->dropColumn(['feedback', 'criteria_id']);
        });
    }
};
