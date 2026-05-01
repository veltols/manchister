<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportService;
use App\Models\SupportServiceStatus;
use App\Models\SupportServiceCategory;
use App\Models\EmployeesList;
use App\Models\SystemLog;

class SupportServiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        // HR admin / root / sys_admin sees all; regular HR sees requests sent to them
        $isAdmin = in_array($user->user_type, ['root', 'sys_admin', 'admin_hr']);

        $query = SupportService::with(['category', 'status', 'sender', 'receiver']);

        if (!$isAdmin) {
            $query->where('sent_to_id', $employeeId);
        }

        $services = $query->orderBy('ss_id', 'desc')->paginate(20);

        $statuses = SupportServiceStatus::orderBy('status_id')->get();

        return view('hr.ss.index', compact('services', 'statuses', 'isAdmin'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $isAdmin = in_array($user->user_type, ['root', 'sys_admin', 'admin_hr']);

        $service = SupportService::with(['category', 'status', 'sender', 'receiver', 'logs.logger'])
            ->findOrFail($id);

        // Restrict access: only admin or the assigned receiver
        if (!$isAdmin && $service->sent_to_id != $employeeId) {
            abort(403, 'You are not authorized to view this request.');
        }

        $statuses = SupportServiceStatus::orderBy('status_id')->get();

        return view('hr.ss.show', compact('service', 'statuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|exists:ss_list_status,status_id',
            'remark'    => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $isAdmin = in_array($user->user_type, ['root', 'sys_admin', 'admin_hr']);

        $service = SupportService::findOrFail($id);

        if (!$isAdmin && $service->sent_to_id != $employeeId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $oldStatusId = $service->status_id;
        $service->status_id = $request->status_id;
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

        // Notify the requester
        \App\Services\NotificationService::send(
            "Your Support Request ({$service->ss_ref}) status has been updated.",
            "emp/ss/{$service->ss_id}",
            $service->added_by
        );

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'new_status' => $service->fresh()->status,
        ]);
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $isAdmin = in_array($user->user_type, ['root', 'sys_admin', 'admin_hr']);
        $perPage = $request->input('per_page', 20);

        $query = SupportService::with(['category', 'status', 'sender', 'receiver']);

        if (!$isAdmin) {
            $query->where('sent_to_id', $employeeId);
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ss_ref', 'like', "%{$search}%")
                  ->orWhere('ss_description', 'like', "%{$search}%");
            });
        }

        $services = $query->orderBy('ss_id', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $services->items(),
            'pagination' => [
                'current_page' => $services->currentPage(),
                'last_page'    => $services->lastPage(),
                'per_page'     => $services->perPage(),
                'total'        => $services->total(),
                'from'         => $services->firstItem(),
                'to'           => $services->lastItem(),
            ],
        ]);
    }
}
