<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeavePermission;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function index()
    {
        $query = LeavePermission::with(['employee', 'status'])->orderBy('permission_id', 'desc');
        
        // Emp Filter
        if(Auth::user()->user_type == 'emp'){
             $query->where('employee_id', Auth::user()->employee_id);
        }
        
        $permissions = $query->paginate(15);
        
        return view('hr.permissions.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'permission_remarks' => 'required|string',
        ]);

        $perm = new LeavePermission();
        $perm->employee_id = Auth::user()->employee_id ?? Auth::id();
        $perm->submission_date = now();
        $perm->start_date = $request->start_date;
        $perm->start_time = $request->start_time;
        $perm->end_time = $request->end_time;
        $perm->total_hours = 2; // Calculation logic needed
        $perm->permission_remarks = $request->permission_remarks;
        $perm->permission_status_id = 1; // Pending
        
        $perm->save();

        return redirect()->back()->with('success', 'Permission Request Submitted');
    }
}
