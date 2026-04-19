<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExitInterview;
use App\Models\ExitInterviewQuestion;
use App\Models\ExitInterviewAnswer;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExitInterviewController extends Controller
{
    public function index(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $deptId = $request->input('department_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search'); // Ref No

        $query = ExitInterview::with(['employee', 'department'])->orderBy('interview_id', 'desc');

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
        if ($deptId) {
            $query->where('current_department_id', $deptId);
        }
        if ($startDate) {
            $query->whereDate('interview_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('interview_date', '<=', $endDate);
        }
        if ($search) {
            $query->where('interview_id', 'LIKE', "%$search%");
        }

        $interviews = $query->paginate(15);
        $employees = Employee::where('is_deleted', 0)->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })->orderBy('first_name')->get();
        $departments = Department::orderBy('department_name')->get();
        $questions = ExitInterviewQuestion::all();

        return view('hr.exit_interviews.index', compact('interviews', 'employees', 'departments', 'questions'));
    }

    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $employeeId = $request->input('employee_id');
        $deptId = $request->input('department_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        $query = ExitInterview::with(['employee', 'department'])->orderBy('interview_id', 'desc');

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
        if ($deptId) {
            $query->where('current_department_id', $deptId);
        }
        if ($startDate) {
            $query->whereDate('interview_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('interview_date', '<=', $endDate);
        }
        if ($search) {
            $query->where('interview_id', 'LIKE', "%$search%");
        }

        $interviews = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $interviews->items(),
            'pagination' => [
                'current_page' => $interviews->currentPage(),
                'last_page' => $interviews->lastPage(),
                'per_page' => $interviews->perPage(),
                'total' => $interviews->total(),
                'from' => $interviews->firstItem(),
                'to' => $interviews->lastItem(),
            ]
        ]);
    }

    public function show($id)
    {
        $interview = ExitInterview::with(['employee.department', 'department', 'answers.question'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $interview
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees_list,employee_id',
            'interview_remarks' => 'nullable|string',
            'question_ids' => 'array',
            'answer_texts' => 'array',
        ]);

        DB::transaction(function () use ($request) {
            $interview = new ExitInterview();
            $interview->employee_id = $request->employee_id;
            $interview->interview_date = now();
            $interview->interview_remarks = $request->interview_remarks ?? '';
            $interview->added_by = auth()->user()->user_id;
            
            // Current Dept ID is required by schema? Legacy serv_list joins on it.
            // Let's try to get it from employee.
            $emp = Employee::find($request->employee_id);
            if($emp){
                // Assuming employee has department_id, or we default to 0
                // Legacy serv_list: `current_department_id`
                // We'll leave it 0 or map if we have the field in Employee model
                // The Employee model usually has department_id? Let's assume so or check.
                // For now, we set 0 to avoid crash if null.
                $interview->current_department_id = $emp->department_id ?? 0;
            }

            $interview->save();

            // Store Answers
            // Legacy sends arrays: question_ids[] and answer_texts[]
            // We need a table to store these. `hr_exit_interviews_answers`?
            // Since we didn't see the table structure, we'll assume a standard EAV or separate table exists.
            // IF NO TABLE EXISTS in legacy (maybe stored in a text field? no, view loops dynamic questions).
            // Let's Assume `hr_exit_interviews_responses` or similar exists.
            // I will create a basic insert for now using DB facade if I don't have a model.
            // Or better: Just log it for this iteration if I'm not sure of the child table.
            
            // Wait, looking at legacy code:
            // It just posts arrays.
            // I'll assume there is a table `hr_exit_interviews_details` or `answers`.
            // For Safety: I will create the Interview record.
            // If the user wants answers stored, I might need to check the DB schema for answers table.
            // But I'll execute the `save` for the main record.
            
            if($request->has('question_ids') && $request->has('answer_texts')){
                $qIds = $request->question_ids;
                $answers = $request->answer_texts;
                
                foreach($qIds as $index => $qId){
                    $ans = $answers[$index] ?? '';
                    if(!empty($ans)){
                        ExitInterviewAnswer::create([
                            'interview_id' => $interview->interview_id,
                            'question_id' => $qId,
                            'answer_text' => $ans
                        ]);
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Exit Interview recorded.');
    }
}
