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
        Schema::table('outbound_communication_attachments', function (Blueprint $table) {
            if (!Schema::hasColumn('outbound_communication_attachments', 'is_final')) {
                $table->boolean('is_final')->default(false)->after('uploaded_by');
            }
        });

        Schema::table('outbound_action_items', function (Blueprint $table) {
            if (!Schema::hasColumn('outbound_action_items', 'assigned_by_id')) {
                $table->unsignedInteger('assigned_by_id')->nullable()->after('communication_id');
            }
            if (Schema::hasColumn('outbound_action_items', 'assigned_to')) {
                $table->renameColumn('assigned_to', 'assigned_to_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('outbound_communication_attachments', function (Blueprint $table) {
            $table->dropColumn('is_final');
        });

        Schema::table('outbound_action_items', function (Blueprint $table) {
            $table->dropColumn('assigned_by_id');
            $table->renameColumn('assigned_to_id', 'assigned_to');
        });
    }
};
