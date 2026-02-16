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
        // Main Information Request Table
        if (!Schema::hasTable('atps_info_request')) {
            Schema::create('atps_info_request', function (Blueprint $table) {
                $table->id('request_id');
                $table->unsignedBigInteger('atp_id');
                $table->date('request_date');
                $table->date('response_date')->nullable();
                $table->integer('request_department')->default(1); // 1 = EQA
                $table->string('request_status', 50)->default('pending'); // pending, submitted
                $table->unsignedBigInteger('added_by');
                $table->timestamp('added_date')->useCurrent();
                $table->string('requester_first_name', 100)->nullable();
                $table->string('requester_last_name', 100)->nullable();
                
                $table->foreign('atp_id')->references('atp_id')->on('atps_list')->onDelete('cascade');
            });
        }

        // Evidence Items Table
        if (!Schema::hasTable('atps_info_request_evs')) {
            Schema::create('atps_info_request_evs', function (Blueprint $table) {
                $table->id('evidence_id');
                $table->unsignedBigInteger('request_id');
                $table->text('required_evidence'); // The evidence requirement description
                $table->text('answer')->nullable(); // ATP's response/comment
                $table->string('required_attachment', 255)->nullable(); // File path
                $table->timestamps();
                
                $table->foreign('request_id')->references('request_id')->on('atps_info_request')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atps_info_request_evs');
        Schema::dropIfExists('atps_info_request');
    }
};
