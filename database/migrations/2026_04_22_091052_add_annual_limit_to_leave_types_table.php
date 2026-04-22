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
        Schema::table('hr_employees_leave_types', function (Blueprint $table) {
            $table->decimal('annual_limit', 8, 2)->default(0)->after('leave_type_name_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_employees_leave_types', function (Blueprint $table) {
            $table->dropColumn('annual_limit');
        });
    }
};
