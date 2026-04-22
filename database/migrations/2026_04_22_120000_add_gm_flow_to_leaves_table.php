<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * New Leave Workflow:
     *  Employee → Line Manager (approve → Pending GM / reject)
     *            → GM (final approve / reject)
     *
     * New status added: 5 = Pending GM
     * New columns: line_manager_id, gm_id, lm_comments, gm_comments,
     *              lm_reviewed_at, gm_reviewed_at
     */
    public function up(): void
    {
        // Add new status row
        DB::table('hr_employees_leave_status')->insertOrIgnore([
            'leave_status_id'      => 5,
            'leave_status_name'    => 'Pending GM',
            'leave_status_name_ar' => 'Pending GM',
        ]);

        // Add workflow columns to leaves table
        Schema::table('hr_employees_leaves', function (Blueprint $table) {
            $table->unsignedInteger('line_manager_id')->nullable()->after('employee_id');
            $table->unsignedInteger('gm_id')->nullable()->after('line_manager_id');
            $table->text('lm_comments')->nullable()->after('leave_remarks');
            $table->text('gm_comments')->nullable()->after('lm_comments');
            $table->dateTime('lm_reviewed_at')->nullable()->after('gm_comments');
            $table->dateTime('gm_reviewed_at')->nullable()->after('lm_reviewed_at');
        });
    }

    public function down(): void
    {
        DB::table('hr_employees_leave_status')->where('leave_status_id', 5)->delete();

        Schema::table('hr_employees_leaves', function (Blueprint $table) {
            $table->dropColumn([
                'line_manager_id', 'gm_id',
                'lm_comments', 'gm_comments',
                'lm_reviewed_at', 'gm_reviewed_at',
            ]);
        });
    }
};
