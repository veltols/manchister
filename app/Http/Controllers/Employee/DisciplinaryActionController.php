<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DisciplinaryAction;
use App\Models\DisciplinaryActionType;
use App\Models\DisciplinaryActionWarning;
use App\Models\DisciplinaryActionStatus;

class DisciplinaryActionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $search = $request->input('search');
        $warningId = $request->input('warning_id');
        $statusId = $request->input('status_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = DisciplinaryAction::with(['type', 'warning', 'status'])
            ->where('employee_id', $employeeId);

        if ($search) {
            $query->where('da_id', 'LIKE', "%$search%");
        }

        if ($warningId) {
            $query->where('da_warning_id', $warningId);
        }

        if ($statusId) {
            $query->where('da_status_id', $statusId);
        }

        if ($startDate) {
            $query->whereDate('da_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('da_date', '<=', $endDate);
        }

        $actions = $query->orderBy('da_id', 'desc')
            ->paginate(15);

        $warnings = DisciplinaryActionWarning::all();
        $statuses = DisciplinaryActionStatus::all();

        return view('emp.da.index', compact('actions', 'warnings', 'statuses'));
    }

    public function show($id)
    {
        $action = DisciplinaryAction::with(['type', 'warning', 'status'])
            ->findOrFail($id);

        return view('emp.da.show', compact('action'));
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $perPage = $request->input('per_page', 15);
        
        $search = $request->input('search');
        $warningId = $request->input('warning_id');
        $statusId = $request->input('status_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = DisciplinaryAction::with(['type', 'warning', 'status'])
            ->where('employee_id', $employeeId);

        if ($search) {
            $query->where('da_id', 'LIKE', "%$search%");
        }

        if ($warningId) {
            $query->where('da_warning_id', $warningId);
        }

        if ($statusId) {
            $query->where('da_status_id', $statusId);
        }

        if ($startDate) {
            $query->whereDate('da_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('da_date', '<=', $endDate);
        }

        $actions = $query->orderBy('da_id', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $actions->items(),
            'pagination' => [
                'current_page' => $actions->currentPage(),
                'last_page' => $actions->lastPage(),
                'per_page' => $actions->perPage(),
                'total' => $actions->total(),
                'from' => $actions->firstItem(),
                'to' => $actions->lastItem(),
            ]
        ]);
    }
}
