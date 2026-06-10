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
            $table->decimal('total_hours', 5, 2)->change();
        });

        Schema::table('employees_list', function (Blueprint $table) {
            $table->decimal('permission_hours_balance', 5, 2)->change();
            $table->decimal('allowed_permission_hours', 5, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_employees_permissions', function (Blueprint $table) {
            $table->integer('total_hours')->change();
        });

        Schema::table('employees_list', function (Blueprint $table) {
            $table->integer('permission_hours_balance')->change();
            $table->integer('allowed_permission_hours')->change();
        });
    }
};
