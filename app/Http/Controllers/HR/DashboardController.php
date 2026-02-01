<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Certificate;
use App\Models\SysList;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Employees
        $totalEmps = Employee::where('is_hidden', 0)->count();

        // 2. Average Age
        $avgAgeRef = Employee::where('is_hidden', 0)
            ->selectRaw('AVG(TIMESTAMPDIFF(YEAR, employee_dob, CURDATE())) as avg_age')
            ->first();
        $averageAge = $avgAgeRef ? (int)$avgAgeRef->avg_age : 0;

        // 3. Gender Diversity (Ratio)
        // Similar to legacy: "10-50.00" (Count-Percentage) - simplified here to just percentage or readable string
        $genders = Employee::groupBy('gender_id')
            ->select('gender_id', DB::raw('count(*) as total'))
            ->get();
        // Assuming we want a specific gender's ratio (e.g., female or just general stats). 
        // Legacy code picked the first result ($QS_DATA[0]) which might be arbitrary if unordered.
        // We will pass the full gender stats to the view for better visualization.
        
        $diversityStat = "N/A";
        if($totalEmps > 0 && $genders->isNotEmpty()){
             // Let's assume we want the count of the largest group for the "Diversity" KPI card for now
             $diversityStat = $genders->first()->total;
        }


        // 4. Charts Data

        // Chart 1: Emp by Dept
        $deptDataLabels = [];
        $deptDataCounts = [];
        $departments = Department::all();
        foreach($departments as $dept) {
            $count = Employee::where('department_id', $dept->department_id)->where('is_hidden', 0)->count();
            if($count > 0) {
                $deptDataLabels[] = $dept->department_name;
                $deptDataCounts[] = $count;
            }
        }

        // Chart 2: Emp by Gender
        $genderDataLabels = [];
        $genderDataCounts = [];
        // Need names for genders from sys_lists
        $genderLists = SysList::where('item_category', 'gender')->get();
        foreach($genderLists as $item) {
            $count = Employee::where('gender_id', $item->item_id)->where('is_hidden', 0)->count();
            if($count > 0) {
                $genderDataLabels[] = $item->item_name;
                $genderDataCounts[] = $count;
            }
        }

        // Chart 3: Emp by Certification
        $certDataLabels = [];
        $certDataCounts = [];
        $certificates = Certificate::all();
        foreach($certificates as $cert) {
            $count = Employee::where('certificate_id', $cert->certificate_id)->where('is_hidden', 0)->count();
            if($count > 0) {
                $certDataLabels[] = $cert->certificate_name;
                $certDataCounts[] = $count;
            }
        }

        return view('hr.dashboard.index', compact(
            'totalEmps', 
            'averageAge', 
            'diversityStat',
            'deptDataLabels', 'deptDataCounts',
            'genderDataLabels', 'genderDataCounts',
            'certDataLabels', 'certDataCounts'
        ));
    }
}
