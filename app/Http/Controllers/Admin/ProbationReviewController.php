<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProbationReview;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

/**
 * GM reviews forwarded probation assessments and makes final decision.
 */
class ProbationReviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->is_gm) abort(403, 'Only the General Manager can access this section.');

        $reviews = ProbationReview::with(['employee', 'lineManager'])
            ->where('gm_id', $user->user_id)
            ->orderByRaw("FIELD(status, 'reviewed', 'pending_manager', 'approved', 'rejected')")
            ->orderBy('review_id', 'desc')
            ->paginate(15);

        return view('admin.probation_reviews.index', compact('reviews'));
    }

    public function show($id)
    {
        $user   = Auth::user();
        if (!$user->is_gm) abort(403);

        $review = ProbationReview::with(['employee', 'lineManager', 'creator'])->findOrFail($id);

        return view('admin.probation_reviews.show', compact('review'));
    }

    /**
     * GM approves the probation review.
     */
    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->is_gm) abort(403);

        $review = ProbationReview::findOrFail($id);

        if ($review->status !== ProbationReview::STATUS_REVIEWED) {
            return redirect()->back()->with('error', 'Review must be in "Reviewed" status to approve.');
        }

        $request->validate(['gm_comments' => 'nullable|string']);

        $review->update([
            'status'          => ProbationReview::STATUS_APPROVED,
            'gm_comments'     => $request->gm_comments,
            'gm_id'           => $user->user_id,
            'gm_reviewed_at'  => now(),
            'updated_at'      => now(),
        ]);

        $this->notifyAfterDecision($review, 'approved');

        return redirect()->back()->with('success', 'Probation review approved successfully.');
    }

    /**
     * GM rejects the probation review.
     */
    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->is_gm) abort(403);

        $review = ProbationReview::findOrFail($id);

        if ($review->status !== ProbationReview::STATUS_REVIEWED) {
            return redirect()->back()->with('error', 'Review must be in "Reviewed" status to reject.');
        }

        $request->validate(['gm_comments' => 'required|string|min:5']);

        $review->update([
            'status'         => ProbationReview::STATUS_REJECTED,
            'gm_comments'    => $request->gm_comments,
            'gm_id'          => $user->user_id,
            'gm_reviewed_at' => now(),
            'updated_at'     => now(),
        ]);

        $this->notifyAfterDecision($review, 'rejected');

        return redirect()->back()->with('success', 'Probation review rejected. HR and Line Manager have been notified.');
    }

    private function notifyAfterDecision(ProbationReview $review, string $decision)
    {
        $empName  = $review->employee->full_name ?? 'Employee';
        $decision = strtoupper($decision);

        // Notify Line Manager
        if ($review->line_manager_id) {
            NotificationService::send(
                "Probation review for {$empName} has been {$decision} by the GM.",
                'emp/probation-reviews',
                $review->line_manager_id
            );
        }

        // Notify HR
        $hrUsers = User::where('user_type', 'hr')->where('is_active', 1)->get();
        foreach ($hrUsers as $hr) {
            NotificationService::send(
                "GM has {$decision} the probation review for {$empName}.",
                'hr/probation-reviews',
                $hr->user_id
            );
        }

        // Notify the Employee
        NotificationService::send(
            "Your probation performance review has been {$decision} by the General Manager.",
            'emp/probation-reviews',
            $review->employee_id
        );
    }
}
