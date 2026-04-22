<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users_list', function (Blueprint $table) {
            $table->boolean('is_gm')->default(0)->after('is_active')
                  ->comment('General Manager');
        });
    }

    public function down(): void
    {
        Schema::table('users_list', function (Blueprint $table) {
            $table->dropColumn('is_gm');
        });
    }
};
