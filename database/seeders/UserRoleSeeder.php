<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\EmployeePass;
use App\Models\LegacyUser;
use App\Models\TrainingProvider;
use App\Models\TrainingProviderPass;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = '123456';
        $hashedPassword = Hash::make($password);

        // 1. ROOT (Super Admin)
        $this->createUser('root', 'Root Admin', 'root@iqc.com', 'root', $hashedPassword);

        // 2. HR Admin
        $this->createUser('hr', 'HR Manager', 'hr@iqc.com', 'hr', $hashedPassword);

        // 3. Employee
        $this->createUser('emp', 'General Employee', 'emp@iqc.com', 'emp', $hashedPassword);

        // 4. Training Provider (ATP)
        $atpEmail = 'atp@iqc.com';
        if (!TrainingProvider::where('atp_email', $atpEmail)->exists()) {
            $atp = TrainingProvider::create([
                'atp_name' => 'Sample Training Center',
                'atp_email' => $atpEmail,
                'atp_ref' => 'ATP-' . time(),
                'status_id' => 3, // Redirects to Portal Dashboard
                'atp_status_id' => 1,
                'atp_category_id' => 1,
                'atp_type_id' => 1,
                'added_date' => now(),
                'added_by' => 1,
            ]);

            TrainingProviderPass::create([
                'atp_id' => $atp->atp_id,
                'pass_value' => $hashedPassword,
            ]);
        }

        $this->command->info('Seeders executed successfully!');
    }

    /**
     * Helper to create a legacy user and linked employee
     */
    private function createUser($username, $name, $email, $role, $hashedPassword)
    {
        if (LegacyUser::where('user_email', $username)->exists()) {
            $this->command->info("User '{$username}' already exists. Skipping.");
            return;
        }

        $nameParts = explode(' ', $name);
        $employee = Employee::create([
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? '',
            'employee_email' => $email,
            'employee_code' => strtoupper($username) . rand(100, 999),
            'employee_type' => 'local',
            'department_id' => 1,
            'designation_id' => 1,
            'added_date' => now(),
            'is_active' => 1,
            'is_deleted' => 0,
            'is_hidden' => 0,
        ]);

        EmployeePass::create([
            'employee_id' => $employee->employee_id,
            'pass_value' => $hashedPassword,
            'is_active' => 1,
            'added_date' => now(),
        ]);

        LegacyUser::create([
            'user_id' => $employee->employee_id,
            'user_email' => $username,
            'user_type' => $role,
            'int_ext' => 'int',
            'is_active' => 1,
        ]);

        $this->command->info("User '{$username}' created successfully.");
    }
}
