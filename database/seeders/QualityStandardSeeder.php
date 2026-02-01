<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QualityStandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('quality_standard_main')->insert([
            ['main_title' => 'Governance & Management', 'main_icon' => 'fa-gavel'],
            ['main_title' => 'Training Resources', 'main_icon' => 'fa-book-open-reader'],
            ['main_title' => 'Assessment & Moderation', 'main_icon' => 'fa-check-double'],
            ['main_title' => 'Quality Assurance', 'main_icon' => 'fa-shield-halved'],
        ]);
    }
}
