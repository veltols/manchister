<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FeedbackForm;
use App\Models\FeedbackAnswer;
use App\Models\SystemLog;

class FeedbackController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        // Check if already submitted
        $alreadySubmitted = FeedbackForm::where('employee_id', $employeeId)->exists();

        if ($alreadySubmitted) {
            return redirect()->route('emp.dashboard')->with('info', 'You have already submitted the feedback form. Thank you!');
        }

        return view('emp.feedback.index');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        // Check if already submitted
        $alreadySubmitted = FeedbackForm::where('employee_id', $employeeId)->exists();
        if ($alreadySubmitted) {
            return redirect()->route('emp.dashboard');
        }

        $form = new FeedbackForm();
        $form->employee_id = $employeeId;
        $form->added_date = now();
        $form->save();

        $answers = new FeedbackAnswer();
        $answers->form_id = $form->form_id;

        // Map a1 to a17
        for ($i = 1; $i <= 17; $i++) {
            $column = 'a' . $i;
            $answers->$column = $request->input($column, '-');
        }

        $answers->save();

        // Log entry
        $log = new SystemLog();
        $log->related_table = 'feedback_forms';
        $log->related_id = $form->form_id;
        $log->log_action = 'Feedback_Submitted';
        $log->log_remark = 'Employee submitted portal feedback';
        $log->log_date = now();
        $log->logged_by = $employeeId;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        return redirect()->route('emp.dashboard')->with('success', 'Your feedback has been submitted successfully. Thank you!');
    }
}
