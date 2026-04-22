<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProbationReview;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

/**
 * Line Manager reviews probation performance and forwards to GM.
 */
class ProbationReviewController extends Controller
{
    public function index()
    {
        $user          = Auth::user();
        $employeeId    = $user->user_id;

        // Line manager sees reviews assigned to them
        $reviews = ProbationReview::with(['employee'])
            ->where('line_manager_id', $employeeId)
            ->orderBy('review_id', 'desc')
            ->paginate(15);

        return view('emp.probation_reviews.index', compact('reviews'));
    }

    public function show($id)
    {
        $user   = Auth::user();
        $review = ProbationReview::with(['employee', 'creator'])->findOrFail($id);

        // Only the assigned line manager can view & act
        if ($review->line_manager_id != $user->user_id) abort(403);

        return view('emp.probation_reviews.show', compact('review'));
    }

    /**
     * Line Manager submits feedback and forwards to GM.
     */
    public function submitReview(Request $request, $id)
    {
        $user   = Auth::user();
        $review = ProbationReview::findOrFail($id);

        if ($review->line_manager_id != $user->user_id) abort(403);

        if ($review->status !== ProbationReview::STATUS_PENDING_MANAGER) {
            return redirect()->back()->with('error', 'This review has already been processed.');
        }

        $request->validate([
            'manager_feedback' => 'required|string',
            'manager_rating'   => 'required|string',
        ]);

        // Find GM user
        $gm = User::where('is_gm', 1)->where('is_active', 1)->first();
        if (!$gm) {
            return redirect()->back()
                ->with('error', 'No General Manager is configured in the system. Please ask Admin to designate a GM.');
        }

        $review->update([
            'status'              => ProbationReview::STATUS_REVIEWED,
            'manager_feedback'    => $request->manager_feedback,
            'manager_rating'      => $request->manager_rating,
            'manager_reviewed_at' => now(),
            'gm_id'              => $gm->user_id,
            'updated_at'          => now(),
        ]);

        // Notify GM
        $empName = $review->employee->full_name ?? 'Employee';
        NotificationService::send(
            "Probation review for {$empName} has been assessed by Line Manager and is awaiting your decision.",
            'probation-reviews/gm',
            $gm->user_id
        );

        // Notify HR
        $hrUsers = User::where('user_type', 'hr')->where('is_active', 1)->get();
        foreach ($hrUsers as $hr) {
            NotificationService::send(
                "Line Manager completed review for {$empName}'s probation. Forwarded to GM.",
                'hr/probation-reviews',
                $hr->user_id
            );
        }

        return redirect()->route('emp.probation-reviews.index')
            ->with('success', 'Review submitted and forwarded to the General Manager.');
    }
}
