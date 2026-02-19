<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tasks_list', function (Blueprint $table) {
            $table->string('task_attachment')->nullable()->after('assigned_by');
        });
    }

    public function down(): void
    {
        Schema::table('tasks_list', function (Blueprint $table) {
            $table->dropColumn('task_attachment');
        });
    }
};
