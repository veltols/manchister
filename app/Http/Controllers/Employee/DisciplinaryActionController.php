<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DisciplinaryAction;
use App\Models\DisciplinaryActionType;
use App\Models\DisciplinaryActionWarning;

class DisciplinaryActionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $actions = DisciplinaryAction::with(['type', 'warning', 'status'])
            ->where('employee_id', $employeeId)
            ->orderBy('da_id', 'desc')
            ->paginate(15);

        return view('emp.da.index', compact('actions'));
    }

    public function show($id)
    {
        $action = DisciplinaryAction::with(['type', 'warning', 'status'])
            ->findOrFail($id);

        return view('emp.da.show', compact('action'));
    }
}
