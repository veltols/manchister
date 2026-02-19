<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id('incident_id');
            $table->dateTime('incident_date');
            $table->string('incident_type');
            $table->text('description');
            $table->string('attachment')->nullable();
            $table->unsignedBigInteger('reported_by')->nullable(); // User ID
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
