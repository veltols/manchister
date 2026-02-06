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

        $categories = SupportServiceCategory::all();
        $employees = EmployeesList::where('is_deleted', 0)->where('is_hidden', 0)->where('employee_id', '!=', $employeeId)->get();

        return view('emp.ss.index', compact('services', 'categories', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'ss_description' => 'required',
            'sent_to_id' => 'required',
            'ss_attachment' => 'nullable|file|max:5120',
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $departmentId = $user->employee ? $user->employee->department_id : 1; // Default to 1 if not set

        $attachment = null;
        if ($request->hasFile('ss_attachment')) {
            $file = $request->file('ss_attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/ss'), $filename);
            $attachment = 'uploads/ss/' . $filename;
        }

        $ss = new SupportService();
        $ss->ss_ref = 'SS-' . strtoupper(substr(uniqid(), -6));
        $ss->category_id = $request->category_id;
        $ss->ss_description = $request->ss_description;
        $ss->ss_attachment = $attachment;
        $ss->department_id = $departmentId;
        $ss->added_by = $employeeId;
        $ss->sent_to_id = $request->sent_to_id;
        $ss->status_id = 1; // Pending
        $ss->ss_added_date = now();
        $ss->save();

        // Log
        // Log creation to be implemented
        // $log = new SystemLog(); ...

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

        return view('emp.ss.show', compact('service'));
    }
}
