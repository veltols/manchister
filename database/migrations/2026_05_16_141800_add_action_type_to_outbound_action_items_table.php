<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('outbound_action_items', function (Blueprint $table) {
            if (!Schema::hasColumn('outbound_action_items', 'action_type')) {
                $table->string('action_type', 50)->default('External')->after('communication_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('outbound_action_items', function (Blueprint $table) {
            $table->dropColumn('action_type');
        });
    }
};
