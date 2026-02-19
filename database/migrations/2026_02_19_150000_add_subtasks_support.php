<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tasks_list', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_task_id')->nullable()->default(0)->after('task_id');
        });
    }

    public function down(): void
    {
        Schema::table('tasks_list', function (Blueprint $table) {
            $table->dropColumn('parent_task_id');
        });
    }
};
