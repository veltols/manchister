<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\EmployeePass;
use App\Models\LegacyUser;

class LoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create Employee
        // Check if exists first to avoid duplicates if run multiple times
        $empEmail = 'admin@iqc.com';
        
        $existing = LegacyUser::where('user_email', 'admin')->first();
        if ($existing) {
            $this->command->info('User "admin" already exists.');
            return;
        }

        // We need to manually manage IDs if auto-increment isn't reliable or if we want specific IDs
        // But usually Eloquent handles it.
        
        $employee = new Employee();
        $employee->first_name = 'Admin';
        $employee->last_name = 'User';
        $employee->employee_email = $empEmail;
        $employee->employee_code = 'ADM001';
        $employee->employee_type = 'local';
        $employee->designation_id = 1; // Dummy
        $employee->department_id = 1; // Dummy
        $employee->save();

        // 2. Create Password
        $pass = new EmployeePass();
        $pass->employee_id = $employee->employee_id;
        $pass->pass_value = Hash::make('123456'); // Password is '123456'
        $pass->is_active = 1;
        $pass->save();

        // 3. Create User Link
        $user = new LegacyUser();
        $user->user_id = $employee->employee_id;
        $user->user_email = 'admin'; // Login username
        $user->user_type = 'emp'; // Give access to emp dashboard
        $user->int_ext = 'int';
        $user->is_active = 1;
        $user->save();

        $this->command->info('User created successfully!');
        $this->command->info('Username: admin');
        $this->command->info('Password: 123456');
    }
}
