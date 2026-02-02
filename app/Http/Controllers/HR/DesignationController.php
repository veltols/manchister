<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Designation;
use App\Models\Department;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::with('department')->orderBy('department_id')->orderBy('designation_name')->paginate(15);
        return view('hr.designations.index', compact('designations'));
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
            'designation_name' => 'required|string|max:255',
            'department_id' => 'required|exists:employees_list_departments,department_id',
        ]);

        Designation::create($request->all());

        return redirect()->route('hr.designations.index')->with('success', 'Designation created successfully.');
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
            'designation_name' => 'required|string|max:255',
            'department_id' => 'required|exists:employees_list_departments,department_id',
        ]);

        $designation = Designation::findOrFail($id);
        $designation->update($request->all());

        return redirect()->route('hr.designations.index')->with('success', 'Designation updated successfully.');
    }

    public function destroy($id)
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();

        return redirect()->route('hr.designations.index')->with('success', 'Designation deleted successfully.');
    }
}
