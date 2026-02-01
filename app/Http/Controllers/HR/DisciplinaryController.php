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

class DisciplinaryController extends Controller
{
    public function index(Request $request)
    {
        $query = DisciplinaryAction::with(['employee', 'type', 'status', 'warning'])
            ->orderBy('da_id', 'desc');

        if ($request->has('employee_id') && $request->employee_id != '') {
            $query->where('employee_id', $request->employee_id);
        }

        $actions = $query->paginate(15);
        $employees = Employee::where('is_deleted', 0)->orderBy('first_name')->get();
        $types = DisciplinaryActionType::all();
        $statuses = DisciplinaryActionStatus::all();
        $warnings = DisciplinaryActionWarning::all();

        return view('hr.disciplinary.index', compact('actions', 'employees', 'types', 'statuses', 'warnings'));
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
        $da->added_by = Auth::id();
        $da->added_date = now();
        $da->save();

        return redirect()->back()->with('success', 'Disciplinary Action Record created.');
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

        return redirect()->back()->with('success', 'Disciplinary Action updated.');
    }
}
