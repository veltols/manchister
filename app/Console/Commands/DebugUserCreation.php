<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class DebugUserCreation extends Command
{
    protected $signature = 'debug:user-create';
    protected $description = 'Debug user creation process';

    public function handle()
    {
        $logFile = public_path('debug.log');
        file_put_contents($logFile, "Starting User Creation Debug...\n");
        
        $this->info("Starting User Creation Debug...");

        try {
            $dept = Department::first();
            if (!$dept) {
                file_put_contents($logFile, "No departments found!\n", FILE_APPEND);
                $this->error("No departments found!");
                return;
            }

            file_put_contents($logFile, "Using Department: {$dept->department_name} (ID: {$dept->department_id})\n", FILE_APPEND);

            $data = [
                'employee_no' => 'DEBUG_' . rand(1000, 9999), 
                'first_name' => 'Debug',
                'last_name' => 'User',
                'email' => 'debug_' . rand(1000, 9999) . '@test.com',
                'password' => 'password123',
                'department_id' => $dept->department_id,
            ];

            DB::beginTransaction();
            
            file_put_contents($logFile, "Creating Employee...\n", FILE_APPEND);
            $employee = new Employee();
            $employee->employee_no = $data['employee_no'];
            $employee->first_name = $data['first_name'];
            $employee->last_name = $data['last_name'];
            $employee->employee_email = $data['email'];
            $employee->department_id = $data['department_id'];
            $employee->employee_code = 'DU';
            $employee->title_id = 0;
            $employee->gender_id = 0;
            $employee->nationality_id = 0;
            $employee->timezone_id = 0;
            $employee->is_deleted = 0;
            $employee->is_hidden = 0;
            $employee->designation_id = 0; // Fix
            $employee->save();
            
            file_put_contents($logFile, "Employee Created. ID: " . $employee->employee_id . "\n", FILE_APPEND);

            file_put_contents($logFile, "Inserting Password...\n", FILE_APPEND);
            DB::table('employees_list_pass')->insert([
                'employee_id' => $employee->employee_id,
                'pass_value' => password_hash($data['password'], PASSWORD_BCRYPT, ["cost" => 12]),
                'is_active' => 1
            ]);

            file_put_contents($logFile, "Inserting Creds...\n", FILE_APPEND);
            DB::table('employees_list_creds')->insert([
                'employee_id' => $employee->employee_id
            ]);

            file_put_contents($logFile, "Fetching User Type...\n", FILE_APPEND);
            $userType = $dept->user_type ?? 'NA';
            file_put_contents($logFile, "User Type: " . $userType . "\n", FILE_APPEND);

            file_put_contents($logFile, "Inserting User List...\n", FILE_APPEND);
            DB::table('users_list')->insert([
                'user_id' => $employee->employee_id,
                'user_email' => $data['email'],
                'user_type' => $userType,
                'int_ext' => 'int',
                'user_family' => 'employees_list'
            ]);

            file_put_contents($logFile, "Committing...\n", FILE_APPEND);
            DB::commit();
            file_put_contents($logFile, "SUCCESS! User created.\n", FILE_APPEND);
            $this->info("SUCCESS! User created.");

        } catch (\Exception $e) {
            DB::rollBack();
            file_put_contents($logFile, "FAILED: " . $e->getMessage() . "\n", FILE_APPEND);
            file_put_contents($logFile, "Trace: " . $e->getTraceAsString() . "\n", FILE_APPEND);
            $this->error("FAILED: " . $e->getMessage());
        }
    }
}
