<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ExitInterview;
use App\Models\ExitInterviewQuestion;
use App\Models\ExitInterviewAnswer;
use App\Models\SystemLog;

class ExitInterviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $interviews = ExitInterview::where('employee_id', $employeeId)
            ->orderBy('interview_date', 'desc')
            ->get();

        $questions = ExitInterviewQuestion::orderBy('question_id', 'asc')->get();

        return view('emp.exit_interview.index', compact('interviews', 'questions'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $interview = new ExitInterview();
        $interview->employee_id = $employeeId;
        $interview->interview_date = now();
        $interview->interview_remarks = $request->input('remarks', '');
        $interview->save();

        $questionIds = $request->input('question_ids', []);
        $answers = $request->input('answers', []);

        foreach ($questionIds as $index => $qId) {
            $ans = new ExitInterviewAnswer();
            $ans->interview_id = $interview->interview_id;
            $ans->question_id = $qId;
            $ans->answer_text = $answers[$index] ?? '';
            $ans->save();
        }

        // Log
        $log = new SystemLog();
        $log->related_table = 'hr_exit_interviews';
        $log->related_id = $interview->interview_id;
        $log->log_action = 'Exit_Interview_Submitted';
        $log->log_remark = 'Employee submitted exit interview form';
        $log->log_date = now();
        $log->logged_by = $employeeId;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        return redirect()->route('emp.dashboard')->with('success', 'Your exit interview has been submitted. We wish you the best in your future endeavors!');
    }
}
