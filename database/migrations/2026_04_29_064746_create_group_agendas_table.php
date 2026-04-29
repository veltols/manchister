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
        Schema::create('z_groups_list_agendas', function (Blueprint $table) {
            $table->id('agenda_id');
            $table->unsignedBigInteger('group_id');
            $table->string('added_by')->nullable();
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('priority')->default('Medium'); // Low, Medium, High, Critical
            $table->string('status')->default('Pending'); // Pending, In Discussion, Completed
            
            $table->dateTime('start_date')->nullable();
            $table->string('time_duration')->nullable(); // e.g., '10 minutes', '1 day 5 hours'
            $table->dateTime('end_date')->nullable();
            
            $table->text('decision_outcome')->nullable();
            $table->text('action_items')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('z_groups_list_agendas');
    }
};
