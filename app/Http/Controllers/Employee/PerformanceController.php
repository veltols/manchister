<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PerformanceReview;

class PerformanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $reviews = PerformanceReview::with('marker')
            ->where('employee_id', $employeeId)
            ->orderBy('added_date', 'desc')
            ->paginate(10);

        return view('emp.performance.index', compact('reviews'));
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $perPage = $request->input('per_page', 10);

        $reviews = PerformanceReview::with('marker')
            ->where('employee_id', $employeeId)
            ->orderBy('added_date', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $reviews->items(),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
                'from' => $reviews->firstItem(),
                'to' => $reviews->lastItem(),
            ]
        ]);
    }
}
