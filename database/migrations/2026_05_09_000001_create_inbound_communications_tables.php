<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ─── 1. External Entities (Received From) ──────────────────────────────
        Schema::create('inbound_external_entities', function (Blueprint $table) {
            $table->increments('entity_id');
            $table->string('entity_name', 200);
            $table->string('entity_code', 10)->unique(); // 2-letter prefix for ref code
            $table->string('entity_email', 200)->nullable();
            $table->string('entity_phone', 50)->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        // ─── 2. Inbound Correspondence (Form A) ────────────────────────────────
        Schema::create('inbound_correspondences', function (Blueprint $table) {
            $table->increments('inbound_id');
            $table->string('reference_code', 30)->unique(); // Auto-generated
            $table->string('correspondence_type', 20)->default('inbound'); // Always inbound, hidden
            $table->unsignedInteger('entity_id');           // Received From
            $table->date('date_of_receipt');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('category', ['hard_copy', 'soft_copy', 'fax', 'whatsapp', 'email', 'other'])->default('soft_copy');
            $table->string('subject', 500);
            $table->text('description')->nullable();        // Brief description / content summary
            $table->text('purpose')->nullable();            // Purpose / reason (mandatory in form)
            $table->string('status', 50)->default('Pending Approval'); // Auto set
            $table->string('digitization_status', 50)->nullable();     // Set by GM (Form B)
            $table->text('gm_comments')->nullable();        // GM reject/modify notes
            $table->unsignedInteger('registered_by');       // Liaison Officer (user_id)
            $table->timestamps();
        });

        // ─── 3. Inbound Attachments ────────────────────────────────────────────
        Schema::create('inbound_attachments', function (Blueprint $table) {
            $table->increments('attachment_id');
            $table->unsignedInteger('inbound_id');
            $table->string('file_name', 300);
            $table->string('file_path', 500);
            $table->string('file_type', 100)->nullable();
            $table->unsignedInteger('uploaded_by');
            $table->timestamps();

            $table->foreign('inbound_id')->references('inbound_id')->on('inbound_correspondences')->onDelete('cascade');
        });

        // ─── 4. Inbound Action Items (Form B — GM assigns to Line Managers) ───
        Schema::create('inbound_action_items', function (Blueprint $table) {
            $table->increments('action_id');
            $table->unsignedInteger('inbound_id');
            $table->string('action_type', 20)->default('internal'); // Always internal, hidden
            $table->unsignedInteger('assigned_to');                 // Line Manager user_id
            $table->string('action_required', 50);                  // Review/Approve/Reject/etc.
            $table->date('due_date')->nullable();
            $table->string('status', 50)->default('Pending');       // Pending/In Progress/Completed/Closed
            $table->text('action_note')->nullable();                 // Line Manager fills this (Form C)
            $table->timestamps();

            $table->foreign('inbound_id')->references('inbound_id')->on('inbound_correspondences')->onDelete('cascade');
        });

        // ─── 5. Seed Default Status Values into app_settings ──────────────────
        DB::table('app_settings')->insertOrIgnore([
            ['key' => 'inbound_digitization_statuses', 'value' => 'Pending,Completed,Verified', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'inbound_action_statuses',        'value' => 'Pending,In Progress,Completed,Closed', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'inbound_action_required_options','value' => 'Review,Approve,Reject,Provide Info,Forward,Archive', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'inbound_correspondence_statuses','value' => 'Pending Approval,Under Review,Approved,Rejected,Modifications Required', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('inbound_action_items');
        Schema::dropIfExists('inbound_attachments');
        Schema::dropIfExists('inbound_correspondences');
        Schema::dropIfExists('inbound_external_entities');

        DB::table('app_settings')->whereIn('key', [
            'inbound_digitization_statuses',
            'inbound_action_statuses',
            'inbound_action_required_options',
            'inbound_correspondence_statuses',
        ])->delete();
    }
};
