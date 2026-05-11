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
        Schema::table('m_communications_list', function (Blueprint $table) {
            if (!Schema::hasColumn('m_communications_list', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('communication_type_id');
            }
            if (!Schema::hasColumn('m_communications_list', 'confidentiality')) {
                $table->enum('confidentiality', ['open', 'confidential', 'restricted'])->default('open')->after('priority');
            }
            if (!Schema::hasColumn('m_communications_list', 'communication_purpose')) {
                $table->text('communication_purpose')->nullable()->after('communication_description');
            }
            // Modification request tracking
            if (!Schema::hasColumn('m_communications_list', 'modification_notes')) {
                $table->text('modification_notes')->nullable();
            }
        });

        // Outbound Attachments
        Schema::create('outbound_communication_attachments', function (Blueprint $table) {
            $table->increments('attachment_id');
            $table->integer('communication_id');
            $table->string('file_name', 300);
            $table->string('file_path', 500);
            $table->string('file_type', 100)->nullable();
            $table->unsignedInteger('uploaded_by');
            $table->timestamps();

            $table->foreign('communication_id')->references('communication_id')->on('m_communications_list')->onDelete('cascade');
        });

        // Outbound Action Items (Assigned by GM in Form 1)
        Schema::create('outbound_action_items', function (Blueprint $table) {
            $table->increments('action_id');
            $table->integer('communication_id');
            $table->unsignedInteger('assigned_to'); // Employee ID
            $table->string('action_required', 100); // Review, Provide Info, etc.
            $table->date('due_date')->nullable();
            $table->string('status', 50)->default('Pending');
            $table->text('completion_note')->nullable();
            $table->timestamps();

            $table->foreign('communication_id')->references('communication_id')->on('m_communications_list')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outbound_action_items');
        Schema::dropIfExists('outbound_communication_attachments');
        Schema::table('m_communications_list', function (Blueprint $table) {
            $table->dropColumn(['priority', 'confidentiality', 'communication_purpose', 'modification_notes']);
        });
    }
};
