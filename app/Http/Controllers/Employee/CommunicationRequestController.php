<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CommunicationRequest;
use App\Models\CommunicationType;
use App\Models\SystemLog;
use App\Services\NotificationService;

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
            'communication_purpose' => 'required',
            'information_shared' => 'required',
            'communication_type_id' => 'required',
            'priority' => 'required|in:low,medium,high',
            'confidentiality' => 'required|in:open,confidential,restricted',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $user = Auth::user();
        $employee = $user->employee;
        $employeeId = $employee ? $employee->employee_id : 0;
        $departmentId = $employee ? $employee->department_id : 1;
        $lineManagerId = ($employee && $employee->department) ? $employee->department->line_manager_id : 0;

        $comm = new CommunicationRequest();
        $comm->communication_code = 'COM-' . strtoupper(substr(uniqid(), -6));
        $comm->external_party_name = $request->external_party_name;
        $comm->communication_subject = $request->communication_subject;
        $comm->communication_description = $request->communication_description;
        $comm->communication_purpose = $request->communication_purpose;
        $comm->information_shared = $request->information_shared;
        $comm->communication_type_id = $request->communication_type_id;
        $comm->priority = $request->priority;
        $comm->confidentiality = $request->confidentiality;
        $comm->communication_status_id = 1; // Pending
        $comm->department_id = $departmentId;
        $comm->requested_by = $employeeId;
        $comm->requested_date = now();
        
        // Form 0 Stage: Set reviewer to Line Manager if exists
        $comm->approval_id_1 = $lineManagerId; 
        
        $comm->save();

        // Handle Attachment
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('communications/outbound', $fileName, 'public');

            \DB::table('outbound_communication_attachments')->insert([
                'communication_id' => $comm->communication_id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getClientMimeType(),
                'uploaded_by' => $employeeId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Log
        $log = new SystemLog();
        $log->related_table = 'm_communications_list';
        $log->related_id = $comm->communication_id;
        $log->log_action = 'Outbound_Requested';
        $log->log_remark = 'Initial request for outbound communication (Form 0)';
        $log->log_date = now();
        $log->logged_by = $employeeId;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        // Notify Line Manager (as per PDF flow)
        if ($lineManagerId != 0) {
            \App\Services\NotificationService::send(
                "New Outbound Comm Request (Form 0) from " . ($employee->employee_name ?? 'Employee') . ", REF: " . $comm->communication_code,
                "hr/communications/list/", // Assuming Line Manager/HR sees it here
                $lineManagerId
            );
        }

        return redirect()->back()->with('success', 'Communication request submitted successfully.');
    }

    public function show($id)
    {
        $request = CommunicationRequest::with(['type', 'status', 'employee', 'attachments'])
            ->findOrFail($id);

        return view('emp.communications.show', compact('request'));
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $perPage = $request->input('per_page', 15);

        $requests = CommunicationRequest::with(['type', 'status'])
            ->where('requested_by', $employeeId)
            ->orderBy('communication_id', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $requests->items(),
            'pagination' => [
                'current_page' => $requests->currentPage(),
                'last_page' => $requests->lastPage(),
                'per_page' => $requests->perPage(),
                'total' => $requests->total(),
                'from' => $requests->firstItem(),
                'to' => $requests->lastItem(),
            ]
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'external_party_name' => 'required',
            'communication_subject' => 'required',
            'communication_description' => 'required',
            'communication_purpose' => 'required',
            'information_shared' => 'required',
            'communication_type_id' => 'required',
            'priority' => 'required|in:low,medium,high',
            'confidentiality' => 'required|in:open,confidential,restricted',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $comm = CommunicationRequest::findOrFail($id);
        $user = Auth::user();
        $employeeId = $user->employee->employee_id ?? 0;

        if ((int)$comm->requested_by !== (int)$employeeId) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $comm->external_party_name = $request->external_party_name;
        $comm->communication_subject = $request->communication_subject;
        $comm->communication_description = $request->communication_description;
        $comm->communication_purpose = $request->communication_purpose;
        $comm->information_shared = $request->information_shared;
        $comm->communication_type_id = $request->communication_type_id;
        $comm->priority = $request->priority;
        $comm->confidentiality = $request->confidentiality;
        
        // Reset flags for restart of flow
        $comm->is_approved_1 = 0; // Back to Line Manager
        $comm->is_approved_2 = 0;
        $comm->communication_status_id = 1; // Pending again
        $comm->save();

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('communications/outbound', $fileName, 'public');

            \App\Models\CommunicationAttachment::create([
                'communication_id' => $comm->communication_id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'uploaded_by' => $employeeId,
            ]);
        }

        SystemLog::create([
            'related_table' => 'm_communications_list',
            'related_id' => $comm->communication_id,
            'log_action' => 'Outbound_Resubmitted',
            'log_remark' => 'Employee updated and resubmitted the request after modification.',
            'log_date' => now(),
            'logged_by' => $employeeId,
            'logger_type' => 'employees_list',
            'log_type' => 'int',
        ]);

        NotificationService::send(
            "Outbound Request REF: " . $comm->communication_code . " has been resubmitted after modification.",
            "emp/lm/communications",
            $comm->approval_id_1
        );

        return redirect()->back()->with('success', 'Request resubmitted successfully.');
    }
}
