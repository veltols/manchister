<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Task;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $view = $request->input('view', 'month');
        $date = $request->input('date', now()->format('Y-m-d'));
        $carbonDate = Carbon::parse($date);

        // Fetch tasks for the current month/week
        $tasks = Task::where('assigned_to', $employeeId)
            ->whereBetween('task_assigned_date', [
                $carbonDate->copy()->startOfMonth()->subDays(7),
                $carbonDate->copy()->endOfMonth()->addDays(7)
            ])
            ->get();

        return view('emp.calendar.index', compact('tasks', 'view', 'date', 'carbonDate'));
    }
}
