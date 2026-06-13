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
        $exists = DB::table('support_tickets_list_status')->where('status_id', 4)->exists();
        if (!$exists) {
            DB::table('support_tickets_list_status')->insert([
                'status_id' => 4,
                'status_name' => 'Cancelled',
                'status_name_ar' => 'Cancelled',
                'status_color' => '7c8a99'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('support_tickets_list_status')->where('status_id', 4)->delete();
    }
};
