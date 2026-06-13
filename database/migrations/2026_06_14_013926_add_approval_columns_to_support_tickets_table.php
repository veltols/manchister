<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_tickets_list', function (Blueprint $table) {
            $table->string('approval_status', 50)->nullable()->default(null);
            $table->integer('approval_sent_to')->nullable()->default(null);
            $table->dateTime('approval_sent_date')->nullable()->default(null);
            $table->text('approval_remarks')->nullable()->default(null);
            $table->dateTime('approval_action_date')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_tickets_list', function (Blueprint $table) {
            $table->dropColumn([
                'approval_status',
                'approval_sent_to',
                'approval_sent_date',
                'approval_remarks',
                'approval_action_date'
            ]);
        });
    }
};
