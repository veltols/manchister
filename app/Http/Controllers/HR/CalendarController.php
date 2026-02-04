<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\HrLeave;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->get('view', 'month');
        $date = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::now();

        $currentMonth = $date->month;
        $currentYear = $date->year;

        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Navigation dates
        $prevDate = $date->copy()->subMonth();
        $nextDate = $date->copy()->addMonth();

        return view('hr.calendar.index', compact(
            'view',
            'date',
            'currentMonth',
            'currentYear',
            'startOfMonth',
            'endOfMonth',
            'prevDate',
            'nextDate'
        ));
    }

    public function getEvents(Request $request)
    {
        $start = Carbon::parse($request->get('start'));
        $end = Carbon::parse($request->get('end'));

        // Fetch Tasks
        $tasks = Task::with('priority')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('task_due_date', [$start, $end]);
            })
            ->get()
            ->map(function ($task) {
                return [
                    'id' => 'task-' . $task->task_id,
                    'title' => $task->task_title,
                    'start' => $task->task_due_date->format('Y-m-d'),
                    'type' => 'task',
                    'color' => $task->priority ? '#' . $task->priority->priority_color : '#6366f1',
                    'url' => route('tasks.index') . '?highlight=' . $task->task_id // Assuming we might handle this
                ];
            });

        // Fetch Leaves (Approved)
        $leaves = HrLeave::with('leaveType')
            ->where('is_approved', 1)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('leave_start_date', [$start, $end])
                    ->orWhereBetween('leave_end_date', [$start, $end]);
            })
            ->get()
            ->map(function ($leave) {
                return [
                    'id' => 'leave-' . $leave->record_id,
                    'title' => $leave->leaveType->leave_type_name ?? 'Leave',
                    'start' => $leave->leave_start_date->format('Y-m-d'),
                    'end' => $leave->leave_end_date->format('Y-m-d'),
                    'type' => 'leave',
                    'color' => '#10b981', // Emerald
                    'url' => route('hr.leaves.index')
                ];
            });

        return response()->json(array_merge($tasks->toArray(), $leaves->toArray()));
    }
}
