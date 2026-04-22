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
        Schema::table('hr_attendance', function (Blueprint $table) {
            $table->date('checkout_date')->nullable()->after('checkin_time');
            $table->time('checkout_time')->nullable()->after('checkout_date');
            $table->decimal('total_hours', 5, 2)->nullable()->after('checkout_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_attendance', function (Blueprint $table) {
            $table->dropColumn(['checkout_date', 'checkout_time', 'total_hours']);
        });
    }
};
