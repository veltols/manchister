<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Root / System Admin
        User::updateOrCreate(
            ['user_email' => 'root@manchister.com'], // Login ID (idder)
            [
                'first_name' => 'System',
                'last_name' => 'Root',
                'password' => Hash::make('123456'), // Default password
                'user_type' => 'root',
                'employee_id' => 1
            ]
        );

        User::updateOrCreate(
            ['user_email' => 'admin@manchister.com'],
            [
                'first_name' => 'System',
                'last_name' => 'Admin',
                'password' => Hash::make('123456'),
                'user_type' => 'sys_admin',
                'employee_id' => 2
            ]
        );

        // 2. HR Manager
        User::updateOrCreate(
            ['user_email' => 'hr@manchister.com'],
            [
                'first_name' => 'Human',
                'last_name' => 'Resources',
                'password' => Hash::make('123456'),
                'user_type' => 'hr',
                'employee_id' => 3
            ]
        );

        // 2.1 Admin HR
        User::updateOrCreate(
            ['user_email' => 'admin_hr@manchister.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Human Resources',
                'password' => Hash::make('123456'),
                'user_type' => 'admin_hr',
                'employee_id' => 31 // Arbitrary ID
            ]
        );


        // 3. Employee
        User::updateOrCreate(
            ['user_email' => 'emp@manchister.com'],
            [
                'first_name' => 'John',
                'last_name' => 'Employee',
                'password' => Hash::make('123456'),
                'user_type' => 'emp',
                'employee_id' => 4
            ]
        );

        // 4. EQA User
        User::updateOrCreate(
            ['user_email' => 'eqa@manchister.com'],
            [
                'first_name' => 'Quality',
                'last_name' => 'Assurance',
                'password' => Hash::make('123456'),
                'user_type' => 'eqa',
                'employee_id' => 5
            ]
        );

        $this->command->info('Users seeded successfully! Password for all is 123456');
    }
}
