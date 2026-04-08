<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel; // If using Laravel Excel, otherwise manual CSV

class FeedbackController extends Controller
{
    public function index()
    {
        // Legacy: feedback_forms joined with answers
        // Assuming we have Feedback models or using DB query as legacy did

        $feedbacks = DB::table('feedback_forms_answers')
            ->join('feedback_forms', 'feedback_forms.form_id', '=', 'feedback_forms_answers.form_id')
            ->join('employees_list', 'employees_list.employee_id', '=', 'feedback_forms.employee_id')
            ->select('feedback_forms_answers.*', 'feedback_forms.added_date', 'employees_list.first_name', 'employees_list.last_name')
            ->orderBy('feedback_forms.added_date', 'desc')
            ->paginate(15);

        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function export()
    {
        $feedbacks = DB::table('feedback_forms_answers')
            ->join('feedback_forms', 'feedback_forms.form_id', '=', 'feedback_forms_answers.form_id')
            ->join('employees_list', 'employees_list.employee_id', '=', 'feedback_forms.employee_id')
            ->select('employees_list.first_name', 'employees_list.last_name', 'feedback_forms.added_date', 'feedback_forms_answers.*')
            ->orderBy('feedback_forms.added_date', 'desc')
            ->get();

        // Map column keys to descriptive headers
        $columnMap = [
            'a1'  => 'UI - User-friendliness',
            'a2'  => 'UI - Visual Appeal',
            'a3'  => 'UI - Login/Logout Experience',
            'a4'  => 'Encountered Technical Issues?',
            'a5'  => 'Technical Issue Details',
            'a6'  => 'Interactive Calendar - Rating',
            'a7'  => 'Interactive Calendar - Comments',
            'a8'  => 'Real-time Messaging - Rating',
            'a9'  => 'Real-time Messaging - Comments',
            'a10' => 'Task Management - Rating',
            'a11' => 'Task Management - Comments',
            'a12' => 'IT Ticket System - Rating',
            'a13' => 'IT Ticket System - Comments',
            'a14' => 'HR Requests Hub - Rating',
            'a15' => 'HR Requests Hub - Comments',
            'a16' => 'Requested Features / Suggestions',
            'a17' => 'Additional Remarks',
        ];

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=portal_feedback_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($feedbacks, $columnMap) {
            $file = fopen('php://output', 'w');

            // Header row with descriptive names
            $headerRow = ['Employee Name', 'Date Submitted'];
            foreach ($columnMap as $label) {
                $headerRow[] = $label;
            }
            fputcsv($file, $headerRow);

            foreach ($feedbacks as $row) {
                $data = [
                    $row->first_name . ' ' . $row->last_name,
                    \Carbon\Carbon::parse($row->added_date)->format('M d, Y h:i A'),
                ];
                foreach (array_keys($columnMap) as $col) {
                    $data[] = $row->$col ?? '';
                }
                fputcsv($file, $data);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 15);

        $feedbacks = DB::table('feedback_forms_answers')
            ->join('feedback_forms', 'feedback_forms.form_id', '=', 'feedback_forms_answers.form_id')
            ->join('employees_list', 'employees_list.employee_id', '=', 'feedback_forms.employee_id')
            ->select('feedback_forms_answers.*', 'feedback_forms.added_date', 'employees_list.first_name', 'employees_list.last_name')
            ->orderBy('feedback_forms.added_date', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $feedbacks->items(),
            'pagination' => [
                'current_page' => $feedbacks->currentPage(),
                'last_page' => $feedbacks->lastPage(),
                'per_page' => $feedbacks->perPage(),
                'total' => $feedbacks->total(),
                'from' => $feedbacks->firstItem(),
                'to' => $feedbacks->lastItem(),
            ]
        ]);
    }
}
