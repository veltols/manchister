<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $tables = [
        'support_tickets_list_cats',
        'hr_employees_leave_types',
        'sys_list_priorities',
        'incident_types',
        'z_assets_list_cats',
        'ss_list_cats',
        'm_communications_list_types',
        'users_list_themes',
        'support_tickets_list_status',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings_tables', function (Blueprint $table) {
            //
        });
    }
};
