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
        Schema::table('inbound_external_entities', function (Blueprint $table) {
            $table->string('contact_person')->nullable()->after('entity_name');
            $table->integer('emirate_id')->nullable()->after('entity_phone');
            $table->integer('category_id')->nullable()->after('emirate_id');
            $table->integer('type_id')->nullable()->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inbound_external_entities', function (Blueprint $table) {
            $table->dropColumn(['contact_person', 'emirate_id', 'category_id', 'type_id']);
        });
    }
};
