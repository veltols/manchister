<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Database manually attached by user, skipping SQL import
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Dropping all tables is risky if we are sharing the DB.
        // But for a migration down, it's expected.
        // We'll leave it empty to protect the data for now.
    }
};
