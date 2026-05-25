<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportService;
use App\Models\SupportServiceCategory;
use App\Models\EmployeesList;
use App\Models\SystemLog;

class SupportServiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $services = SupportService::with(['category', 'status', 'sender', 'receiver'])
            ->where('added_by', $employeeId)
            ->orWhere('sent_to_id', $employeeId)
            ->orderBy('ss_id', 'desc')
            ->paginate(15);

        $categories = SupportServiceCategory::with('receiver')->get();
        $employees = EmployeesList::where('is_deleted', 0)->where('is_hidden', 0)->where('employee_id', '!=', $employeeId)->get();

        return view('emp.ss.index', compact('services', 'categories', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'ss_description' => 'required',
            'sent_to_id' => 'nullable', // Made optional, will be fetched from category if empty
            'ss_attachment' => 'nullable|file|max:5120',
        ]);

        $category = SupportServiceCategory::findOrFail($request->category_id);
        $finalReceiverId = $category->destination_id ?: $request->sent_to_id;

        if (!$finalReceiverId || $finalReceiverId == '0') {
            return redirect()->back()->with('error', 'This request receiver is not available. Please contact admin.')->withInput();
        }

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $departmentId = $user->employee ? $user->employee->department_id : 1; 

        $attachment = null;
        if ($request->hasFile('ss_attachment')) {
            $file = $request->file('ss_attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/ss'), $filename);
            $attachment = 'uploads/ss/' . $filename;
        }

        $ss = new SupportService();
        $ss->category_id = $request->category_id;
        $ss->ss_description = $request->ss_description;
        $ss->ss_attachment = $attachment;
        $ss->department_id = $departmentId;
        $ss->added_by = $employeeId;
        $ss->sent_to_id = $finalReceiverId;
        $ss->status_id = 1; // Pending
        $ss->ss_added_date = now();

        \Illuminate\Support\Facades\DB::transaction(function () use ($ss) {
            $yymm = date('ym');
            $lastTicket = SupportService::where('ss_ref', 'like', "SS-{$yymm}%")
                                        ->lockForUpdate()
                                        ->orderBy('ss_id', 'desc')
                                        ->first();
            if ($lastTicket) {
                $lastCount = intval(substr($lastTicket->ss_ref, -3));
                $newCount = $lastCount + 1;
            } else {
                $newCount = 1;
            }
            
            $ss_ref = 'SS-' . $yymm . str_pad($newCount, 3, '0', STR_PAD_LEFT);
            
            while (SupportService::where('ss_ref', $ss_ref)->exists()) {
                $newCount++;
                $ss_ref = 'SS-' . $yymm . str_pad($newCount, 3, '0', STR_PAD_LEFT);
            }
            
            $ss->ss_ref = $ss_ref;
            $ss->save();
        });

        $log = new SystemLog();
        $log->related_table = 'ss_list';
        $log->related_id    = $ss->ss_id;
        $log->log_action    = 'Request Created';
        $log->log_remark    = 'Support request was successfully created';
        $log->log_date      = now();
        $log->logger_type   = 'employees_list';
        $log->logged_by     = $employeeId;
        $log->save();

        // Send Notifications
        \App\Services\NotificationService::send(
            "A new Support Request has been added, REF: " . $ss->ss_ref,
            "ss/list/", 
            $ss->added_by
        );

        \App\Services\NotificationService::send(
            "A new Support Request has been sent to you, REF: " . $ss->ss_ref,
            "ss/list/", 
            $ss->sent_to_id
        );

        return redirect()->back()->with('success', 'Service request sent successfully');
    }

    public function show($id)
    {
        $service = SupportService::with(['category', 'status', 'sender', 'receiver', 'logs.logger'])
            ->findOrFail($id);
        
        $statuses = \App\Models\SupportServiceStatus::all();

        return view('emp.ss.show', compact('service', 'statuses'));
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $perPage = $request->input('per_page', 15);

        $services = SupportService::with(['category', 'status', 'sender', 'receiver'])
            ->where(function($query) use ($employeeId) {
                $query->where('added_by', $employeeId)
                      ->orWhere('sent_to_id', $employeeId);
            })
            ->orderBy('ss_id', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $services->items(),
            'pagination' => [
                'current_page' => $services->currentPage(),
                'last_page' => $services->lastPage(),
                'per_page' => $services->perPage(),
                'total' => $services->total(),
                'from' => $services->firstItem(),
                'to' => $services->lastItem(),
            ]
        ]);
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|exists:ss_list_status,status_id',
            'remark'    => 'nullable|string',
            'result_attachment' => 'nullable|file|max:5120'
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $service = SupportService::findOrFail($id);

        // Only the receiver can update the status
        if ($service->sent_to_id != $employeeId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $oldStatusId = $service->status_id;
        $service->status_id = $request->status_id;
        
        if ($request->remark) {
            $service->ss_remarks = $request->remark;
        }

        if ($request->hasFile('result_attachment')) {
            $file = $request->file('result_attachment');
            $filename = time() . '_res_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/ss_results'), $filename);
            $service->ss_result_attachment = 'uploads/ss_results/' . $filename;
        }

        $service->save();

        // Log the status change
        $log = new SystemLog();
        $log->related_table = 'ss_list';
        $log->related_id    = $service->ss_id;
        $log->log_action    = 'Status Updated';
        $log->log_remark    = $request->remark ?: ('Status changed to ' . ($service->fresh()->status->status_name ?? 'N/A'));
        $log->log_date      = now();
        $log->logger_type   = 'employees_list';
        $log->logged_by     = $employeeId;
        $log->save();

        // Notify the requester (sender)
        \App\Services\NotificationService::send(
            "Your Support Request ({$service->ss_ref}) status has been updated.",
            "emp/ss/{$service->ss_id}",
            $service->added_by
        );

        return response()->json([
            'success' => true, 
            'message' => 'Status updated successfully',
            'status_name' => $service->fresh()->status->status_name,
            'status_color' => $service->fresh()->status->status_color
        ]);
    }
}
