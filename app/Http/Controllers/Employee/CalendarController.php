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

        $startDate = $carbonDate->copy();
        $endDate = $carbonDate->copy();

        if ($view === 'day') {
            $startDate = $carbonDate->copy()->startOfDay();
            $endDate = $carbonDate->copy()->endOfDay();
        } elseif ($view === 'week') {
            $startDate = $carbonDate->copy()->startOfWeek(Carbon::SUNDAY);
            $endDate = $carbonDate->copy()->endOfWeek(Carbon::SATURDAY);
        } else {
            // Month View
            $startDate = $carbonDate->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
            $endDate = $carbonDate->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);
        }

        // Fetch tasks for the calculated range
        $tasks = Task::where('assigned_to', $employeeId)
            ->whereBetween('task_assigned_date', [$startDate, $endDate])
            ->get();

        // Data for New Task Modal
        $employees = \App\Models\Employee::where('is_deleted', 0)->where('is_hidden', 0)->get();
        $priorities = \App\Models\Priority::all();

        return view('emp.calendar.index', compact('tasks', 'view', 'date', 'carbonDate', 'startDate', 'endDate', 'employees', 'priorities'));
    }
}
