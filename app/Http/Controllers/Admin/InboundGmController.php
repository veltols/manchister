<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InboundCorrespondence;
use App\Models\InboundActionItem;
use App\Models\User;
use App\Models\Department;
use App\Models\AppSetting;

/**
 * GM Controller for Inbound Correspondence
 * Only users with is_gm = 1 can access this module.
 * Handles Form B: Review, decide, assign action items to Line Managers.
 */
class InboundGmController extends Controller
{
    /**
     * Guard: Ensure only the GM can access this module.
     */
    protected function ensureGm()
    {
        $user = Auth::user();
        if (!$user || !$user->is_gm) {
            abort(403, 'Access denied. Only the designated General Manager can access this module.');
        }
    }

    // ── Index: GM inbox — correspondences routed to this GM ──────────────────
    public function index(Request $request)
    {
        $this->ensureGm();

        $query = InboundCorrespondence::with(['entity', 'registeredBy', 'actionItems'])
            ->where('gm_user_id', Auth::user()->user_id) // Only correspondences sent to this GM
            ->orderBy('inbound_id', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->paginate(15);

        return view('admin.inbound.index', compact('records'));
    }

    // ── show: Form A (read-only) + Form B (decision + action items) ──────────
    public function show($id)
    {
        $this->ensureGm();

        $record = InboundCorrespondence::with([
            'entity', 'registeredBy', 'attachments', 'actionItems.assignedTo',
        ])->where('gm_user_id', Auth::user()->user_id)->findOrFail($id);

        // Line Managers = users who are set as line_manager_id in any department
        $lineManagerIds = Department::whereNotNull('line_manager_id')
                            ->where('line_manager_id', '!=', 0)
                            ->pluck('line_manager_id');

        $lineManagers = User::whereIn('user_id', $lineManagerIds)
                            ->where('is_active', 1)
                            ->with('employee')
                            ->get();

        $digitizationStatus     = $this->getSettingValues('inbound_digitization_statuses');
        $actionStatuses         = $this->getSettingValues('inbound_action_statuses');
        $actionRequired         = $this->getSettingValues('inbound_action_required_options');
        $correspondenceStatuses = $this->getSettingValues('inbound_correspondence_statuses');

        return view('admin.inbound.show', compact(
            'record', 'lineManagers', 'digitizationStatus',
            'actionStatuses', 'actionRequired', 'correspondenceStatuses'
        ));
    }

    // ── decide: GM approves / rejects / requests modifications ───────────────
    public function decide(Request $request, $id)
    {
        $this->ensureGm();

        $request->validate([
            'decision'            => 'required|in:approved,rejected,modifications_required',
            'gm_comments'         => 'nullable|string',
            'digitization_status' => 'nullable|string',
        ]);

        $record = InboundCorrespondence::where('gm_user_id', Auth::user()->user_id)->findOrFail($id);

        $statusMap = [
            'approved'               => 'Approved',
            'rejected'               => 'Rejected',
            'modifications_required' => 'Modifications Required',
        ];

        $record->update([
            'status'              => $statusMap[$request->decision],
            'gm_comments'         => $request->gm_comments,
            'digitization_status' => $request->digitization_status,
        ]);

        return redirect()->route('admin.inbound.show', $id)
            ->with('success', 'Decision recorded: ' . $statusMap[$request->decision]);
    }

    // ── storeActionItem: GM assigns one or more action items (bulk) ───────────
    public function storeActionItem(Request $request, $id)
    {
        $this->ensureGm();

        $request->validate([
            'items'                   => 'required|array|min:1',
            'items.*.assigned_to'     => 'required|exists:users_list,user_id',
            'items.*.action_required' => 'required|string',
            'items.*.due_date'        => 'required|date',
        ]);

        $record = InboundCorrespondence::where('gm_user_id', Auth::user()->user_id)->findOrFail($id);

        foreach ($request->items as $item) {
            InboundActionItem::create([
                'inbound_id'      => $record->inbound_id,
                'assigned_by'     => Auth::user()->user_id, // GM's user_id
                'action_type'     => 'internal',
                'assigned_to'     => $item['assigned_to'], // Line Manager's user_id
                'action_required' => $item['action_required'],
                'due_date'        => $item['due_date'],
                'status'          => $item['status'] ?? 'Pending',
            ]);
        }

        // Move to "Under Review" once action items are assigned
        if (in_array($record->status, ['Pending Approval', 'Resubmitted', 'Approved'])) {
            $record->update(['status' => 'Under Review']);
        }

        $count = count($request->items);
        return redirect()->route('admin.inbound.show', $id)
            ->with('success', "{$count} action item(s) assigned to Line Manager(s) successfully.");
    }

    // ── destroyActionItem: Remove an action item ──────────────────────────────
    public function destroyActionItem($id, $actionId)
    {
        $this->ensureGm();

        InboundActionItem::where('inbound_id', $id)->where('action_id', $actionId)->delete();
        return redirect()->route('admin.inbound.show', $id)
            ->with('success', 'Action item removed.');
    }

    // ── Helper ────────────────────────────────────────────────────────────────
    protected function getSettingValues(string $key): array
    {
        $setting = AppSetting::where('key', $key)->first();
        if (!$setting || !$setting->value) {
            return [];
        }
        return array_map('trim', explode(',', $setting->value));
    }
}
