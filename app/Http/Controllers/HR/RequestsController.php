<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrLeave;
use App\Models\LeavePermission;
use App\Models\DisciplinaryAction;
use App\Models\Attendance;
use App\Models\Performance;
use App\Models\ExitInterview;
use App\Models\HrDocument;
use App\Models\HrGroup;
use App\Models\Task;

class RequestsController extends Controller
{
    public function index()
    {
        $stats = [
            'leaves' => HrLeave::count(),
            'permissions' => LeavePermission::count(),
            'disciplinary' => DisciplinaryAction::count(),
            'attendance' => Attendance::count(),
            'exit_interviews' => ExitInterview::count(),
            'performance' => Performance::count(),
            'documents' => HrDocument::count(),
            'groups' => HrGroup::count(),
            'tasks' => Task::count(),
        ];

        return view('hr.requests.index', compact('stats'));
    }
}
