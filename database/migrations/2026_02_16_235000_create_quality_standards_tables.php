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
        if (!Schema::hasTable('quality_standards')) {
            Schema::create('quality_standards', function (Blueprint $table) {
                $table->increments('qs_id');
                $table->integer('main_id');
                $table->string('qs_title')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('quality_standards_cats')) {
            Schema::create('quality_standards_cats', function (Blueprint $table) {
                $table->increments('cat_id');
                $table->integer('qs_id');
                $table->string('cat_ref')->nullable();
                $table->text('cat_description')->nullable();
                $table->integer('is_main')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_standards_cats');
        Schema::dropIfExists('quality_standards');
    }
};
