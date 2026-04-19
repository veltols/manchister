<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;

class SystemLogController extends Controller
{
    public function index(Request $request)
    {
        $query = SystemLog::with(['logger'])->where('logger_type','admin')->where('logged_by',Auth::user()->user_id)->orderBy('log_id', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('log_action', 'LIKE', "%{$search}%")
                  ->orWhere('log_remark', 'LIKE', "%{$search}%")
                  ->orWhere('related_table', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('logger_type', $request->type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('log_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('log_date', '<=', $request->end_date);
        }

        $logs = $query->paginate(30);

        return view('admin.system_logs.index', compact('logs'));
    }

    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 30);
        $query = SystemLog::with(['logger'])->where('logger_type','admin')->where('logged_by',Auth::user()->user_id)->orderBy('log_id', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('log_action', 'LIKE', "%{$search}%")
                  ->orWhere('log_remark', 'LIKE', "%{$search}%")
                  ->orWhere('related_table', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('logger_type', $request->type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('log_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('log_date', '<=', $request->end_date);
        }

        $logs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $logs->items(),
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'total' => $logs->total(),
                'from' => $logs->firstItem(),
                'to' => $logs->lastItem(),
            ]
        ]);
    }
}
