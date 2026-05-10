<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inbound_correspondences', function (Blueprint $table) {
            // Track which GM this correspondence was sent to for review
            $table->unsignedInteger('gm_user_id')->nullable()->after('registered_by');
        });

        Schema::table('inbound_action_items', function (Blueprint $table) {
            // assigned_to must reference users_list.user_id (not employee_id)
            // No structural change needed — just documenting the intent
            // Add assigned_by so we know which GM assigned this action
            $table->unsignedInteger('assigned_by')->nullable()->after('inbound_id');
        });
    }

    public function down(): void
    {
        Schema::table('inbound_correspondences', function (Blueprint $table) {
            $table->dropColumn('gm_user_id');
        });
        Schema::table('inbound_action_items', function (Blueprint $table) {
            $table->dropColumn('assigned_by');
        });
    }
};
