<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Probation Performance Review Workflow:
     *
     *  HR  ──creates──►  Line Manager (review + feedback)
     *      ──forwards──►  GM  (approve / reject with comments)
     *
     * Statuses:
     *   pending_manager  — waiting for line manager review
     *   reviewed         — line manager reviewed, forwarded to GM
     *   approved         — GM approved
     *   rejected         — GM rejected
     */
    public function up(): void
    {
        Schema::create('hr_probation_reviews', function (Blueprint $table) {
            $table->increments('review_id');

            // The employee being reviewed
            $table->unsignedInteger('employee_id');

            // Review type/title (e.g. "End of Probation", "Mid Probation")
            $table->string('review_title')->default('Probation Performance Review');

            // Probation period context
            $table->string('probation_type')->nullable();       // initial / extended / completed
            $table->date('probation_end_date')->nullable();

            // HR fills in objectives & KPIs
            $table->text('objectives')->nullable();
            $table->text('kpis')->nullable();
            $table->text('hr_notes')->nullable();

            // Workflow status
            $table->enum('status', [
                'pending_manager',   // HR sent → waiting Line Manager
                'reviewed',          // Line Manager reviewed → forwarded to GM
                'approved',          // GM approved
                'rejected',          // GM rejected
            ])->default('pending_manager');

            // Line manager
            $table->unsignedInteger('line_manager_id')->nullable();
            $table->text('manager_feedback')->nullable();
            $table->string('manager_rating')->nullable();  // 1-5 or Excellent/Good/etc.
            $table->dateTime('manager_reviewed_at')->nullable();

            // GM
            $table->unsignedInteger('gm_id')->nullable();
            $table->text('gm_comments')->nullable();
            $table->dateTime('gm_reviewed_at')->nullable();

            // Audit
            $table->unsignedInteger('created_by');       // HR user_id
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_probation_reviews');
    }
};
