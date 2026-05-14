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

        // Check for privileged roles
        $isLM = \App\Models\Department::where('line_manager_id', $employeeId)->exists();
        $isGM = $user->is_gm;
        $isLiaison = $user->is_liaison;

        // Redirect regular employees to Request Center
        if (!$isLM && !$isGM && !$isLiaison) {
            return redirect()->route('emp.requests.index');
        }

        // Calculate counts for Inbound
        $empGmInboundPending = 0;
        if ($user->is_gm) {
            $empGmInboundPending = \App\Models\InboundCorrespondence::where('gm_user_id', $user->user_id)
                ->whereIn('status', ['Pending Approval', 'Under Review', 'Resubmitted'])->count();
        }
        
        $inboundActionCount = 0;
        if (!$user->is_gm) {
            $inboundActionCount = InboundActionItem::where('assigned_to', $user->user_id)
                ->whereIn('status', ['Pending', 'In Progress'])
                ->count();
        }
        
        // Total Inbound depends on role
        $inboundCount = ($user->is_gm ? $empGmInboundPending : $inboundActionCount);

        // Calculate counts for Outbound
        $isLM = \App\Models\Department::where('line_manager_id', $employeeId)->exists();
        
        // Hide LM, Liaison, and personal tasks from GM
        $lmCommPending = ($isLM && !$user->is_gm) ? CommunicationRequest::where('approval_id_1', $employeeId)->where('is_approved_1', 0)->count() : 0;
        
        $gmOutboundPending = $user->is_gm ? CommunicationRequest::where('is_approved_1', 1)->where('is_approved_2', 0)->count() : 0;
        
        $liaisonOutboundPending = ($user->is_liaison && !$user->is_gm) ? CommunicationRequest::where('is_approved_2', 1)->where('communication_status_id', 3)->count() : 0;
        
        $outboundTaskCount = 0;
        if (!$user->is_gm) {
            $outboundTaskCount = OutboundActionItem::where('assigned_to_id', $employeeId)
                ->whereIn('status', ['Pending', 'In Progress'])
                ->count();
        }
        
        // Total Outbound for GM is ONLY GM pending
        $outboundCount = $user->is_gm ? $gmOutboundPending : ($outboundTaskCount + $lmCommPending + $liaisonOutboundPending);

        return view('emp.communication_hub.index', compact(
            'inboundCount', 
            'outboundCount',
            'empGmInboundPending',
            'inboundActionCount',
            'lmCommPending',
            'gmOutboundPending',
            'liaisonOutboundPending',
            'outboundTaskCount'
        ));
    }
}
