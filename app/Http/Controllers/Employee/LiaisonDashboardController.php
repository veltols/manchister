<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiaisonDashboardController extends Controller
{
    /**
     * Display the liaison dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        // Add basic metrics for the dashboard
        $metrics = [
            'external_entities' => \App\Models\InboundExternalEntity::count(),
            'inbound_communications' => \App\Models\InboundCorrespondence::where('registered_by', $employeeId)->count(),
            'communications_log' => \App\Models\SystemLog::whereIn('related_table', ['m_communications_list', 'inbound_correspondences', 'outbound_action_items'])->count(),
            'outbound_pending' => \App\Models\CommunicationRequest::where('is_approved_2', 1)->where('communication_status_id', 3)->count()
        ];

        return view('emp.liaison.dashboard', compact('metrics'));
    }
}
