<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProbationReview;
use App\Models\Employee;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class ProbationReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = ProbationReview::with(['employee', 'lineManager', 'creator'])
            ->orderBy('review_id', 'desc');

        if ($request->filled('status'))      $query->where('status', $request->status);
        if ($request->filled('employee_id')) $query->where('employee_id', $request->employee_id);

        $reviews   = $query->paginate(15);
        $employees = Employee::where('is_deleted', 0)
            ->whereHas('systemUser', fn($q) => $q->where('is_active', 1))
            ->orderBy('first_name')->get();

        return view('hr.probation_reviews.index', compact('reviews', 'employees'));
    }

    /**
     * HR creates a probation review and sends it to the employee's line manager.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id'       => 'required|exists:employees_list,employee_id',
            'review_title'      => 'nullable|string|max:255',
            'probation_type'    => 'nullable|string',
            'probation_end_date'=> 'nullable|date',
            'objectives'        => 'required|string',
            'kpis'              => 'required|string',
            'hr_notes'          => 'nullable|string',
        ]);

        $employee      = Employee::findOrFail($request->employee_id);
        $department    = $employee->department;
        $lineManagerId = $department?->line_manager_id;

        if (!$lineManagerId) {
            return redirect()->back()
                ->with('error', "Employee's department has no Line Manager assigned. Please set a Line Manager first.");
        }

        $review = ProbationReview::create([
            'employee_id'        => $request->employee_id,
            'review_title'       => $request->review_title ?: 'Probation Performance Review',
            'probation_type'     => $request->probation_type     ?: $employee->probation_type,
            'probation_end_date' => $request->probation_end_date ?: $employee->probation_end_date,
            'objectives'         => $request->objectives,
            'kpis'               => $request->kpis,
            'hr_notes'           => $request->hr_notes,
            'status'             => ProbationReview::STATUS_PENDING_MANAGER,
            'line_manager_id'    => $lineManagerId,
            'created_by'         => Auth::user()->user_id,
            'created_at'         => now(),
        ]);

        // Notify Line Manager
        NotificationService::send(
            "New Probation Review request for {$employee->full_name} requires your assessment.",
            'emp/probation-reviews',
            $lineManagerId
        );

        SystemLog($review->review_id, 'HR created probation review and sent to Line Manager.');

        return redirect()->back()
            ->with('success', "Probation review created and sent to Line Manager of {$employee->full_name}.");
    }

    public function show($id)
    {
        $review = ProbationReview::with(['employee', 'lineManager', 'creator'])->findOrFail($id);
        return view('hr.probation_reviews.show', compact('review'));
    }

    public function destroy($id)
    {
        $review = ProbationReview::findOrFail($id);
        if ($review->status !== ProbationReview::STATUS_PENDING_MANAGER) {
            return redirect()->back()->with('error', 'Only pending reviews can be deleted.');
        }
        $review->delete();
        return redirect()->back()->with('success', 'Review deleted.');
    }
}

// Helper
function SystemLog($reviewId, $remark)
{
    \App\Models\SystemLog::create([
        'related_table' => 'hr_probation_reviews',
        'related_id'    => $reviewId,
        'log_date'      => now(),
        'log_action'    => 'Probation_Review',
        'log_remark'    => $remark,
        'logger_type'   => 'users_list',
        'logged_by'     => Auth::user()->user_id,
        'log_type'      => 'int',
    ]);
}
