<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users_list_themes')->insert([
            'theme_name' => 'Premium Teal',
            'color_primary' => '00384a', // Darker shade (Bottom)
            'color_secondary' => '004F68', // Brand color (Top)
            'color_text' => 'ffffff',
            'is_deleted' => 0
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users_list_themes')->where('theme_name', 'Premium Teal')->delete();
    }
};
