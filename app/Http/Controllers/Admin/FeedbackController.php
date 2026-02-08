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
        // Simple CSV Export matching legacy tableToExcel JS function conceptually but server-side
        $feedbacks = DB::table('feedback_forms_answers')
            ->join('feedback_forms', 'feedback_forms.form_id', '=', 'feedback_forms_answers.form_id')
            ->join('employees_list', 'employees_list.employee_id', '=', 'feedback_forms.employee_id')
            ->select('employees_list.first_name', 'employees_list.last_name', 'feedback_forms.added_date', 'feedback_forms_answers.*')
            ->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=portal_feedback.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($feedbacks) {
            $file = fopen('php://output', 'w');

            // Header Row
            $columns = ['Employee', 'Date'];
            for ($i = 1; $i <= 17; $i++)
                $columns[] = "Q$i"; // Simplified headers
            fputcsv($file, $columns);

            foreach ($feedbacks as $row) {
                $data = [
                    $row->first_name . ' ' . $row->last_name,
                    $row->added_date
                ];
                for ($i = 1; $i <= 17; $i++) {
                    $col = "a$i";
                    $data[] = $row->$col;
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
