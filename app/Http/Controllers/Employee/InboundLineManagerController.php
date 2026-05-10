<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InboundCorrespondence;
use App\Models\InboundActionItem;

/**
 * Line Manager (Employee) Controller for Inbound Correspondence
 * Only users who have action items assigned to them can access this.
 * Handles Form C: View assigned action items and submit action notes.
 */
class InboundLineManagerController extends Controller
{
    // ── Index: List action items assigned to this Line Manager ────────────────
    public function index(Request $request)
    {
        $userId = Auth::user()->user_id; // user_id from users_list — matches assigned_to in action items

        $query = InboundActionItem::with([
            'correspondence.entity',
            'correspondence.attachments',
        ])
        ->where('assigned_to', $userId)
        ->orderBy('due_date', 'asc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $actionItems = $query->paginate(15);

        return view('emp.inbound.index', compact('actionItems'));
    }

    // ── show: Full detail of one action item (Form A + Form B info + Form C input) ──
    public function show($actionId)
    {
        $userId = Auth::user()->user_id; // user_id from users_list

        $actionItem = InboundActionItem::with([
            'correspondence.entity',
            'correspondence.attachments',
            'correspondence.actionItems',
        ])
        ->where('assigned_to', $userId)
        ->where('action_id', $actionId)
        ->firstOrFail();

        return view('emp.inbound.show', compact('actionItem'));
    }

    // ── submitNote: Line Manager submits their action note (Form C) ───────────
    public function submitNote(Request $request, $actionId)
    {
        $userId = Auth::user()->user_id; // user_id from users_list

        $request->validate([
            'action_note' => 'required|string',
            'status'      => 'required|in:Pending,In Progress,Completed,Closed',
        ]);

        $actionItem = InboundActionItem::where('assigned_to', $userId)
            ->where('action_id', $actionId)
            ->firstOrFail();

        $actionItem->update([
            'action_note' => $request->action_note,
            'status'      => $request->status,
        ]);

        // If ALL action items on this correspondence are Completed/Closed,
        // automatically mark the correspondence as Approved
        $correspondence = $actionItem->correspondence;
        $allDone = $correspondence->actionItems()
            ->whereNotIn('status', ['Completed', 'Closed'])
            ->doesntExist();

        if ($allDone && $correspondence->status === 'Under Review') {
            $correspondence->update(['status' => 'Approved']);
        }

        return redirect()->route('emp.inbound.show', $actionId)
            ->with('success', 'Action note submitted successfully.');
    }
}
