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
        Schema::table('hr_employees_permissions', function (Blueprint $table) {
            $table->integer('line_manager_id')->default(0)->after('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_employees_permissions', function (Blueprint $table) {
            $table->dropColumn('line_manager_id');
        });
    }
};
