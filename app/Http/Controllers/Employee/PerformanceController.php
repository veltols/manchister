<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PerformanceReview;

class PerformanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $reviews = PerformanceReview::with('marker')
            ->where('employee_id', $employeeId)
            ->orderBy('added_date', 'desc')
            ->paginate(10);

        return view('emp.performance.index', compact('reviews'));
    }
}
