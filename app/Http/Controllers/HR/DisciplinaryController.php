<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DisciplinaryAction;
use App\Models\DisciplinaryActionType;
use App\Models\DisciplinaryActionStatus;
use App\Models\DisciplinaryActionWarning;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class DisciplinaryController extends Controller
{
    public function index(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $warningId = $request->input('warning_id');
        $statusId = $request->input('status_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search'); // Ref No

        $query = DisciplinaryAction::with(['employee', 'type', 'status', 'warning'])
            ->orderBy('da_id', 'desc');

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
        if ($warningId) {
            $query->where('da_warning_id', $warningId);
        }
        if ($statusId) {
            $query->where('da_status_id', $statusId);
        }
        if ($startDate) {
            $query->whereDate('added_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('added_date', '<=', $endDate);
        }
        if ($search) {
            $query->where('da_id', 'LIKE', "%$search%");
        }

        $actions = $query->paginate(15);
        $employees = Employee::where('is_deleted', 0)->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })->orderBy('first_name')->get();
        $types = DisciplinaryActionType::all();
        $statuses = DisciplinaryActionStatus::all();
        $warnings = DisciplinaryActionWarning::all();

        return view('hr.disciplinary.index', compact('actions', 'employees', 'types', 'statuses', 'warnings'));
    }

    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $employeeId = $request->input('employee_id');
        $warningId = $request->input('warning_id');
        $statusId = $request->input('status_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        $query = DisciplinaryAction::with(['employee', 'type', 'status', 'warning'])
            ->orderBy('da_id', 'desc');

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
        if ($warningId) {
            $query->where('da_warning_id', $warningId);
        }
        if ($statusId) {
            $query->where('da_status_id', $statusId);
        }
        if ($startDate) {
            $query->whereDate('added_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('added_date', '<=', $endDate);
        }
        if ($search) {
            $query->where('da_id', 'LIKE', "%$search%");
        }

        $actions = $query->paginate($perPage);

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

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees_list,employee_id',
            'da_type_id' => 'required|exists:hr_disp_actions_types,da_type_id',
            'da_warning_id' => 'required|exists:hr_disp_actions_warnings,da_warning_id',
            'da_remark' => 'nullable|string',
        ]);

        $da = new DisciplinaryAction();
        $da->employee_id = $request->employee_id;
        $da->da_type_id = $request->da_type_id;
        $da->da_warning_id = $request->da_warning_id;
        $da->da_remark = $request->da_remark ?? '';
        $da->da_status_id = 1; // Default to 'Pending' or similar? Legacy used hardcoded values or dropdown? View shows dropdown starts with 'Please Select' but code implies creation defaults?
        // In legacy `addNewDA` JS: `$('.new-da_id').val(1);` ... actually `addNewController` usually inserts.
        // Let's assume Status 1 is "Draft" or "Issued". We'll default to 1 if not specified.
        $da->added_by = auth()->user()->user_id;
        $da->added_date = now();
        $da->save();

        // Notify Employee
        $type = DisciplinaryActionType::find($da->da_type_id);
        $warning = DisciplinaryActionWarning::find($da->da_warning_id);
        $msg = "New disciplinary record has been issued: " . ($type->da_type_code ?? 'Formal Action') . " - " . ($warning->da_warning_name ?? 'Level');
        NotificationService::send($msg, 'emp.da.index#da-container', $da->employee_id);

        return redirect()->back()->with('success', 'Disciplinary Action Record created and employee notified.');
    }

    public function update(Request $request, $id)
    {
        $da = DisciplinaryAction::findOrFail($id);
        
        if($request->has('da_status_id')){
            $da->da_status_id = $request->da_status_id;
        }
        
        if($request->has('da_remark')){
            $da->da_remark = $request->da_remark;
        }

        $da->save();

        // Notify Employee of update
        $status = DisciplinaryActionStatus::find($da->da_status_id);
        $msg = "Disciplinary Record Update: Action #" . $da->da_id . " status changed to " . ($status->da_status_name ?? 'Updated');
        NotificationService::send($msg, 'emp.da.show?id=' . $da->da_id, $da->employee_id);

        return redirect()->back()->with('success', 'Disciplinary Action updated and employee notified.');
    }
}
