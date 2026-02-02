<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CommunicationRequest;
use App\Models\CommunicationType;
use App\Models\SystemLog;

class CommunicationRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $requests = CommunicationRequest::with(['type', 'status'])
            ->where('requested_by', $employeeId)
            ->orderBy('communication_id', 'desc')
            ->paginate(15);

        $types = CommunicationType::all();

        return view('emp.communications.index', compact('requests', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'external_party_name' => 'required',
            'communication_subject' => 'required',
            'communication_description' => 'required',
            'information_shared' => 'required',
            'communication_type_id' => 'required',
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $departmentId = $user->employee ? $user->employee->department_id : 1; // Default to 1 if not set

        $comm = new CommunicationRequest();
        $comm->communication_code = 'COM-' . strtoupper(substr(uniqid(), -6));
        $comm->external_party_name = $request->external_party_name;
        $comm->communication_subject = $request->communication_subject;
        $comm->communication_description = $request->communication_description;
        $comm->information_shared = $request->information_shared;
        $comm->communication_type_id = $request->communication_type_id;
        $comm->communication_status_id = 1; // Pending
        $comm->department_id = $departmentId;
        $comm->requested_by = $employeeId;
        $comm->requested_date = now();
        $comm->save();

        // Log
        $log = new SystemLog();
        $log->related_table = 'm_communications_list';
        $log->related_id = $comm->communication_id;
        $log->log_action = 'Communication_Requested';
        $log->log_remark = 'Initial request for external communication';
        $log->log_date = now();
        $log->logged_by = $employeeId;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        return redirect()->back()->with('success', 'Communication request submitted successfully');
    }

    public function show($id)
    {
        $request = CommunicationRequest::with(['type', 'status', 'employee'])
            ->findOrFail($id);

        return view('emp.communications.show', compact('request'));
    }
}
