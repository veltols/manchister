<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users_list', function (Blueprint $table) {
            $table->tinyInteger('feedback_enabled')->default(1)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users_list', function (Blueprint $table) {
            $table->dropColumn('feedback_enabled');
        });
    }
};
