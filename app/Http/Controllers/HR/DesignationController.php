<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Designation;
use App\Models\Department;

class DesignationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Designation::with('department');

        if ($search) {
            $query->where('designation_name', 'LIKE', '%' . $search . '%');
        }

        $designations = $query->orderBy('designation_id', 'desc')
            ->paginate(15);
        $departments = Department::orderBy('department_name')->get();

        return view('hr.designations.index', compact('designations', 'departments'));
    }

    public function getData(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $query = Designation::with('department');

        if ($search) {
            $query->where('designation_name', 'LIKE', '%' . $search . '%');
        }

        $designations = $query->orderBy('designation_id', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $designations->items(),
            'pagination' => [
                'current_page' => $designations->currentPage(),
                'last_page' => $designations->lastPage(),
                'per_page' => $designations->perPage(),
                'total' => $designations->total(),
                'from' => $designations->firstItem(),
                'to' => $designations->lastItem(),
            ]
        ]);
    }

    public function create()
    {
        $departments = Department::orderBy('department_name')->get();
        return view('hr.designations.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'designation_code' => 'required|string|max:50|unique:employees_list_designations,designation_code',
            'designation_name' => 'required|string|max:255|unique:employees_list_designations,designation_name',
            'department_id' => 'required|exists:employees_list_departments,department_id',
            'log_remark' => 'nullable|string',
        ], [
            'designation_name.unique' => 'This designation already exists.',
            'designation_code.unique' => 'This designation code already exists.',
        ]);

        $designation = new Designation();
        $designation->designation_code = $request->designation_code;
        $designation->designation_name = $request->designation_name;
        $designation->department_id = $request->department_id;
        $designation->save();

        $this->logAction($designation->designation_id, 'Designation_Added', $request->log_remark ?? '---');

        $prefix = request()->is('admin*') ? 'admin' : 'hr';
        return redirect()->back()->with('success', 'Designation created successfully.');
    }

    public function edit($id)
    {
        $designation = Designation::findOrFail($id);
        $departments = Department::orderBy('department_name')->get();
        return view('hr.designations.edit', compact('designation', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'designation_code' => 'required|string|max:50|unique:employees_list_designations,designation_code,' . $id . ',designation_id',
            'designation_name' => 'required|string|max:255|unique:employees_list_designations,designation_name,' . $id . ',designation_id',
            'department_id' => 'required|exists:employees_list_departments,department_id',
            'log_remark' => 'required|string',
        ], [
            'designation_name.unique' => 'This designation already exists.',
            'designation_code.unique' => 'This designation code already exists.',
        ]);

        $designation = Designation::findOrFail($id);
        $designation->designation_code = $request->designation_code;
        $designation->designation_name = $request->designation_name;
        $designation->department_id = $request->department_id;
        $designation->save();

        $this->logAction($designation->designation_id, 'Designation_Updated', $request->log_remark);

        $prefix = request()->is('admin*') ? 'admin' : 'hr';
        return redirect()->back()->with('success', 'Designation updated successfully.');
    }

    public function destroy($id)
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();

        $prefix = request()->is('admin*') ? 'admin' : 'hr';
        return redirect()->route($prefix . '.designations.index')->with('success', 'Designation deleted successfully.');
    }

    private function logAction($id, $action, $remark)
    {
        \App\Models\SystemLog::create([
            'related_table' => 'employees_list_designations',
            'related_id' => $id,
            'log_date' => now(),
            'log_action' => $action,
            'log_remark' => $remark,
            'logger_type' => 'employees_list',
            'logged_by' => \Illuminate\Support\Facades\Auth::id() ?? 1,
            'log_type' => 'int'
        ]);
    }
}
