<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InboundActionItem;
use App\Models\CommunicationRequest;
use App\Models\OutboundActionItem;

class CommunicationHubController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        // Calculate counts for Inbound
        $empGmInboundPending = 0;
        if ($user->is_gm) {
            $empGmInboundPending = \App\Models\InboundCorrespondence::where('gm_user_id', $user->user_id)
                ->whereIn('status', ['Pending Approval', 'Under Review', 'Resubmitted'])->count();
        }
        $inboundActionCount = InboundActionItem::where('assigned_to', $user->user_id)
            ->whereIn('status', ['Pending', 'In Progress'])
            ->count();
        $inboundCount = $empGmInboundPending + $inboundActionCount;

        // Calculate counts for Outbound
        $lmCommPending = CommunicationRequest::where('approval_id_1', $employeeId)
            ->where('is_approved_1', 0)->count();
        $gmOutboundPending = CommunicationRequest::where('is_approved_1', 1)
            ->where('is_approved_2', 0)->count();
        $liaisonOutboundPending = CommunicationRequest::where('is_approved_2', 1)
            ->where('communication_status_id', 3)->count();
        $outboundTaskCount = OutboundActionItem::where('assigned_to_id', $employeeId)
            ->whereIn('status', ['Pending', 'In Progress'])
            ->count();
        
        $outboundCount = $lmCommPending + $gmOutboundPending + $liaisonOutboundPending + $outboundTaskCount;

        return view('emp.communication_hub.index', compact('inboundCount', 'outboundCount'));
    }
}
