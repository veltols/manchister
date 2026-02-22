<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks_list', function (Blueprint $table) {
            $table->tinyInteger('is_rejected')->default(0)->after('pending_line_manager_id');
            $table->text('rejection_reason')->nullable()->after('is_rejected');
        });
    }

    public function down(): void
    {
        Schema::table('tasks_list', function (Blueprint $table) {
            $table->dropColumn(['is_rejected', 'rejection_reason']);
        });
    }
};
