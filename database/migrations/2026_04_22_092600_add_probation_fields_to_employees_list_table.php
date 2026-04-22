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
        Schema::table('employees_list', function (Blueprint $row) {
            $row->string('probation_type')->nullable()->after('emp_status_id');
            $row->date('probation_end_date')->nullable()->after('probation_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees_list', function (Blueprint $row) {
            $row->dropColumn(['probation_type', 'probation_end_date']);
        });
    }
};
