<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id('comment_id');
            $table->unsignedBigInteger('task_id')->index();
            $table->unsignedBigInteger('employee_id')->index();
            $table->text('comment_body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_comments');
    }
};
