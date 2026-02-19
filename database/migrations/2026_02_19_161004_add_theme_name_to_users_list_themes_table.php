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
        Schema::table('users_list_themes', function (Blueprint $table) {
            if (!Schema::hasColumn('users_list_themes', 'theme_name')) {
                $table->string('theme_name')->nullable()->after('user_theme_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_list_themes', function (Blueprint $table) {
             if (Schema::hasColumn('users_list_themes', 'theme_name')) {
                $table->dropColumn('theme_name');
            }
        });
    }
};
