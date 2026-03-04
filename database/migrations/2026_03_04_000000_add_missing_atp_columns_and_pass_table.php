<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ─── 1. Create atps_list_pass if it doesn't exist ────────────────────
        if (!Schema::hasTable('atps_list_pass')) {
            Schema::create('atps_list_pass', function (Blueprint $table) {
                $table->increments('pass_id');
                $table->string('pass_value', 70)->comment('bcrypt hash');
                $table->integer('atp_id');
                $table->tinyInteger('is_active')->default(1);
            });
        }

        // ─── 2. Create atps_list_cancel if missing ────────────────────────────
        if (!Schema::hasTable('atps_list_cancel')) {
            Schema::create('atps_list_cancel', function (Blueprint $table) {
                $table->increments('cancel_id');
                $table->integer('atp_id');
                $table->dateTime('submission_date')->nullable();
                $table->dateTime('approved_date')->nullable();
                $table->integer('cancellation_reason')->default(0);
                $table->text('cancellation_text')->nullable();
                $table->string('cancel_status', 30)->default('pending');
            });
        }

        // ─── 3. Add missing columns to atps_list ─────────────────────────────
        Schema::table('atps_list', function (Blueprint $table) {
            if (!Schema::hasColumn('atps_list', 'accreditation_type')) {
                $table->integer('accreditation_type')->default(1)->after('atp_type_id')
                    ->comment('1=New Accreditation, 2=Renewal');
            }
            if (!Schema::hasColumn('atps_list', 'atp_name_ar')) {
                $table->text('atp_name_ar')->nullable()->after('atp_name');
            }
            if (!Schema::hasColumn('atps_list', 'atp_logo')) {
                $table->string('atp_logo', 70)->default('no-img.png')->after('atp_name_ar');
            }
            if (!Schema::hasColumn('atps_list', 'area_name')) {
                $table->string('area_name', 100)->nullable()->after('emirate_id');
            }
            if (!Schema::hasColumn('atps_list', 'street_name')) {
                $table->string('street_name', 100)->nullable()->after('area_name');
            }
            if (!Schema::hasColumn('atps_list', 'building_name')) {
                $table->string('building_name', 100)->nullable()->after('street_name');
            }
            if (!Schema::hasColumn('atps_list', 'phase_id')) {
                $table->integer('phase_id')->default(1)->after('atp_status_id');
            }
            if (!Schema::hasColumn('atps_list', 'is_phase_ok')) {
                $table->tinyInteger('is_phase_ok')->default(1)->after('phase_id');
            }
            if (!Schema::hasColumn('atps_list', 'todo_id')) {
                $table->integer('todo_id')->default(1)->after('is_phase_ok');
            }
            if (!Schema::hasColumn('atps_list', 'last_updated')) {
                $table->dateTime('last_updated')->nullable()->after('added_date');
            }
        });

        // ─── 4. Seed atps_list_status if empty ───────────────────────────────
        $statusCount = DB::table('atps_list_status')->count();
        if ($statusCount == 0) {
            DB::table('atps_list_status')->insert([
                ['atp_status_id' => 1, 'atp_status_name' => 'Pending Email', 'atp_status_name_ar' => 'بانتظار إرسال البريد الإلكتروني', 'atp_status_description' => 'Pending registration link to be sent to the ATP', 'atp_status_description_ar' => ''],
                ['atp_status_id' => 2, 'atp_status_name' => 'Pending', 'atp_status_name_ar' => 'بانتظار تعبئة نموذج التسجيل', 'atp_status_description' => 'Pending ATP to fill initial registration form', 'atp_status_description_ar' => ''],
                ['atp_status_id' => 3, 'atp_status_name' => 'Accredited', 'atp_status_name_ar' => 'معتمد', 'atp_status_description' => 'Accredited', 'atp_status_description_ar' => ''],
                ['atp_status_id' => 4, 'atp_status_name' => 'Renewal', 'atp_status_name_ar' => 'تجديد', 'atp_status_description' => 'Renewal in progress', 'atp_status_description_ar' => ''],
                ['atp_status_id' => 5, 'atp_status_name' => 'Expired', 'atp_status_name_ar' => 'منتهي', 'atp_status_description' => 'Accreditation expired', 'atp_status_description_ar' => ''],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atps_list_pass');
        Schema::dropIfExists('atps_list_cancel');

        Schema::table('atps_list', function (Blueprint $table) {
            $table->dropColumn([
                'accreditation_type',
                'atp_name_ar',
                'atp_logo',
                'area_name',
                'street_name',
                'building_name',
                'phase_id',
                'is_phase_ok',
                'todo_id',
                'last_updated',
            ]);
        });
    }
};
