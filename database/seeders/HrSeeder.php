<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\EmployeePass;
use App\Models\LegacyUser;

class HrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create Employee
        $empEmail = 'hr@iqc.com';
        
        $existing = LegacyUser::where('user_email', 'hr_user')->first();
        if ($existing) {
            $this->command->info('User "hr_user" already exists.');
            return;
        }

        $employee = new Employee();
        $employee->first_name = 'HR';
        $employee->last_name = 'Manager';
        $employee->employee_email = $empEmail;
        $employee->employee_code = 'HR001';
        $employee->employee_type = 'local';
        $employee->designation_id = 99; // Dummy
        $employee->department_id = 99; // Dummy
        // HR usually has is_hidden=0
        $employee->is_hidden = 0; 
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
        $user->user_email = 'hr_user'; // Login username
        $user->user_type = 'hr'; // HR Access
        $user->int_ext = 'int';
        $user->is_active = 1;
        $user->save();

        $this->command->info('HR User created successfully!');
        $this->command->info('Username: hr_user');
        $this->command->info('Password: 123456');
    }
}
