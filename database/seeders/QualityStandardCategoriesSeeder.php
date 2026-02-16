<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QualityStandardCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Governance & Management
        $qs1 = DB::table('quality_standards')->insertGetId([
            'main_id' => 1,
            'qs_title' => 'Management Systems',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('quality_standards_cats')->insert([
            ['qs_id' => $qs1, 'cat_ref' => '1.1', 'cat_description' => 'Legal status and governance of the institution', 'created_at' => now(), 'updated_at' => now()],
            ['qs_id' => $qs1, 'cat_ref' => '1.2', 'cat_description' => 'Strategic planning and management processes', 'created_at' => now(), 'updated_at' => now()],
            ['qs_id' => $qs1, 'cat_ref' => '1.3', 'cat_description' => 'Financial management and sustainability', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Training Resources
        $qs2 = DB::table('quality_standards')->insertGetId([
            'main_id' => 2,
            'qs_title' => 'Resources and Equipment',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('quality_standards_cats')->insert([
            ['qs_id' => $qs2, 'cat_ref' => '2.1', 'cat_description' => 'Suitability of premises and facilities', 'created_at' => now(), 'updated_at' => now()],
            ['qs_id' => $qs2, 'cat_ref' => '2.2', 'cat_description' => 'Availability of equipment and software', 'created_at' => now(), 'updated_at' => now()],
            ['qs_id' => $qs2, 'cat_ref' => '2.3', 'cat_description' => 'Staff recruitment and induction', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Assessment & Moderation
        $qs3 = DB::table('quality_standards')->insertGetId([
            'main_id' => 3,
            'qs_title' => 'Assessment Design',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('quality_standards_cats')->insert([
            ['qs_id' => $qs3, 'cat_ref' => '3.1', 'cat_description' => 'Design of assessment instruments', 'created_at' => now(), 'updated_at' => now()],
            ['qs_id' => $qs3, 'cat_ref' => '3.2', 'cat_description' => 'Security of assessment materials', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. Quality Assurance
        $qs4 = DB::table('quality_standards')->insertGetId([
            'main_id' => 4,
            'qs_title' => 'Internal Quality Assurance',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('quality_standards_cats')->insert([
            ['qs_id' => $qs4, 'cat_ref' => '4.1', 'cat_description' => 'Internal monitoring and review processes', 'created_at' => now(), 'updated_at' => now()],
            ['qs_id' => $qs4, 'cat_ref' => '4.2', 'cat_description' => 'Continuous improvement mechanisms', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
