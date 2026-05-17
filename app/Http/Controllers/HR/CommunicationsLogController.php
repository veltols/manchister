<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InboundCorrespondence;
use App\Models\CommunicationRequest;
use Illuminate\Support\Facades\Auth;

class CommunicationsLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->is_liaison) {
            abort(403, 'Access denied. Only Liaison Officers can access this module.');
        }

        // Fetch Inbound
        $inbounds = InboundCorrespondence::with(['entity'])
            ->where('registered_by', $user->user_id)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->inbound_id,
                    'type' => 'Inbound',
                    'ref_code' => $item->reference_code,
                    'subject' => $item->subject,
                    'entity' => $item->entity ? $item->entity->entity_name : '-',
                    'date' => $item->date_of_receipt,
                    'status' => $item->status,
                    'priority' => $item->priority,
                    'color' => 'indigo'
                ];
            });

        // Fetch Outbound (Approved by GM so Liaison handles it)
        $outbounds = CommunicationRequest::with(['status'])
            ->where('is_approved_2', 1)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->communication_id,
                    'type' => 'Outbound',
                    'ref_code' => $item->communication_code ?? 'Pending Ref',
                    'subject' => $item->communication_subject,
                    'entity' => $item->external_party_name ?? '-',
                    'date' => date('Y-m-d', strtotime($item->approved_2_date ?? $item->created_at)),
                    'status' => $item->status ? $item->status->communication_status_name : 'Pending Dispatch',
                    'priority' => 'Medium', // Outbound might not have priority field, default to Medium
                    'color' => 'purple'
                ];
            });

        $logs = $inbounds->concat($outbounds)->sortByDesc('date');

        // Fetch System Logs for Liaison Officer (Only Communication Related)
        $sysLogs = \App\Models\SystemLog::where('logged_by', $user->user_id)
            ->whereIn('related_table', ['m_communications_list', 'inbound_correspondences', 'outbound_action_items'])
            ->orderBy('log_date', 'desc')
            ->get();

        return view('hr.communications.log.index', compact('logs', 'sysLogs'));
    }
}
