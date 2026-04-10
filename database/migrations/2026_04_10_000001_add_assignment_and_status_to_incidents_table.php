<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_person_1')->nullable()->after('reported_by');
            $table->unsignedBigInteger('assigned_person_2')->nullable()->after('assigned_person_1');
            $table->unsignedBigInteger('assigned_person_3')->nullable()->after('assigned_person_2');
            $table->enum('status', ['pending', 'resolved'])->default('pending')->after('assigned_person_3');
        });
    }

    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropColumn(['assigned_person_1', 'assigned_person_2', 'assigned_person_3', 'status']);
        });
    }
};
