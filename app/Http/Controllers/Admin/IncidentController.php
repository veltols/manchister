<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\IncidentType;
use App\Models\EmployeesList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\SystemLog;

class IncidentController extends Controller
{
    public function index()
    {
        $incidents  = Incident::with('reporter.employee')->latest()->paginate(10);
        $types      = IncidentType::orderBy('type_name')->get();
        $employees  = EmployeesList::whereHas('systemUser', function($q) {
            $q->where('is_active', 1);
        })->where('is_deleted', 0)->where('is_hidden', 0)->orderBy('first_name')->get();
        return view('admin.incidents.index', compact('incidents', 'types', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'incident_date_only' => 'required|date',
            'incident_time_only' => 'required',
            'incident_type'      => 'required|string',
            'description'        => 'required|string',
            'attachment'         => 'nullable|file|max:10240',
            'assigned_person_1'  => 'nullable|integer',
            'assigned_person_2'  => 'nullable|integer',
            'assigned_person_3'  => 'nullable|integer',
            'status'             => 'nullable|in:pending,resolved',
        ]);

        $incident = new Incident();
        $incident->incident_date    = $request->incident_date_only . ' ' . $request->incident_time_only;
        $incident->incident_type    = $request->incident_type;
        $incident->description      = $request->description;
        $incident->assigned_person_1 = $request->assigned_person_1 ?: null;
        $incident->assigned_person_2 = $request->assigned_person_2 ?: null;
        $incident->assigned_person_3 = $request->assigned_person_3 ?: null;
        $incident->status            = $request->status ?? 'pending';

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = 'incident_' . time() . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/incidents');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $incident->attachment = 'uploads/incidents/' . $filename;
        }

        $incident->reported_by = Auth::id();
        $incident->save();

        $this->logAction($incident->incident_id, 'Incident Created', $this->buildLogRemark($incident));

        return redirect()->back()->with('success', 'Incident recorded successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'incident_date_only' => 'required|date',
            'incident_time_only' => 'required',
            'incident_type'      => 'required|string',
            'description'        => 'required|string',
            'attachment'         => 'nullable|file|max:10240',
            'assigned_person_1'  => 'nullable|integer',
            'assigned_person_2'  => 'nullable|integer',
            'assigned_person_3'  => 'nullable|integer',
            'status'             => 'nullable|in:pending,resolved',
        ]);

        $incident = Incident::findOrFail($id);
        $incident->incident_date     = $request->incident_date_only . ' ' . $request->incident_time_only;
        $incident->incident_type     = $request->incident_type;
        $incident->description       = $request->description;
        $incident->assigned_person_1 = $request->assigned_person_1 ?: null;
        $incident->assigned_person_2 = $request->assigned_person_2 ?: null;
        $incident->assigned_person_3 = $request->assigned_person_3 ?: null;
        $incident->status            = $request->status ?? 'pending';

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = 'incident_' . time() . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('uploads/incidents');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $incident->attachment = 'uploads/incidents/' . $filename;
        }

        $incident->save();

        $this->logAction($incident->incident_id, 'Incident Updated', $this->buildLogRemark($incident));

        return redirect()->route('admin.incidents.index')->with('success', 'Incident updated successfully.');
    }

    public function destroy($id)
    {
        $incident = Incident::findOrFail($id);
        $type = $incident->incident_type;
        $incident->delete();

        $this->logAction($id, 'Incident Deleted', "Incident deleted: " . $type);

        return response()->json(['success' => true, 'message' => 'Incident deleted successfully.']);
    }

    public function show($id)
    {
        $incident = Incident::findOrFail($id);
        return view('admin.incidents.show', compact('incident'));
    }

    public function getData(Request $request)
    {
        $page    = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search  = $request->get('search');
        $date    = $request->get('date');
        $reporter = $request->get('reporter');

        $query = Incident::with('reporter.employee', 'assignedPerson1', 'assignedPerson2', 'assignedPerson3')
                         ->latest('incident_date');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('incident_type', 'like', "%{$search}%")
                  ->orWhereHas('reporter.employee', function ($sq) use ($search) {
                      $sq->where(\Illuminate\Support\Facades\DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
                         ->orWhere('employee_no', 'like', "%{$search}%");
                  });
            });
        }

        if ($date) {
            $query->whereDate('incident_date', $date);
        }

        if ($reporter) {
            $query->where(function ($q) use ($reporter) {
                $q->whereHas('reporter.employee', function ($sq) use ($reporter) {
                    $sq->where(\Illuminate\Support\Facades\DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$reporter}%")
                       ->orWhere('first_name', 'like', "%{$reporter}%")
                       ->orWhere('last_name', 'like', "%{$reporter}%")
                       ->orWhere('employee_no', 'like', "%{$reporter}%");
                })->orWhereHas('reporter', function ($sq) use ($reporter) {
                    $sq->where('user_email', 'like', "%{$reporter}%");
                });
            });
        }

        $incidents = $query->paginate($perPage);

        $incidents->getCollection()->transform(function ($incident) {
            $reporterName = optional(optional($incident->reporter)->employee)->first_name
                ?? optional($incident->reporter)->user_email
                ?? 'System';

            $getName = fn($emp) => $emp ? trim($emp->first_name . ' ' . $emp->last_name) : null;

            $incident->reporter_name        = $reporterName;
            $incident->formatted_date       = \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y');
            $incident->formatted_time       = \Carbon\Carbon::parse($incident->incident_date)->format('h:i A');
            $incident->raw_date             = \Carbon\Carbon::parse($incident->incident_date)->format('Y-m-d\TH:i');
            $incident->attachment_url       = $incident->attachment ? asset($incident->attachment) : null;
            $incident->assigned_person_1_name = $getName($incident->assignedPerson1);
            $incident->assigned_person_2_name = $getName($incident->assignedPerson2);
            $incident->assigned_person_3_name = $getName($incident->assignedPerson3);
            return $incident;
        });

        return response()->json([
            'success' => true,
            'data'    => $incidents->items(),
            'pagination' => [
                'current_page' => $incidents->currentPage(),
                'last_page'    => $incidents->lastPage(),
                'per_page'     => $incidents->perPage(),
                'total'        => $incidents->total(),
                'from'         => $incidents->firstItem(),
                'to'           => $incidents->lastItem(),
            ]
        ]);
    }

    /**
     * Build a descriptive remark for the system log including
     * incident type, status, and all assigned persons.
     */
    private function buildLogRemark(Incident $incident): string
    {
        $getName = function ($empId) {
            if (!$empId) return null;
            $emp = EmployeesList::find($empId);
            return $emp ? trim($emp->first_name . ' ' . $emp->last_name) : "Employee #{$empId}";
        };

        $assignees = array_filter([
            $getName($incident->assigned_person_1),
            $getName($incident->assigned_person_2),
            $getName($incident->assigned_person_3),
        ]);

        $status    = ucfirst($incident->status ?? 'pending');
        $assignStr = count($assignees) > 0 ? implode(', ', $assignees) : 'Unassigned';

        return "Type: {$incident->incident_type} | Status: {$status} | Assigned To: {$assignStr}";
    }

    private function logAction($refId, $action, $remark, $table = 'incidents')
    {
        $log = new SystemLog();
        $log->related_id    = $refId;
        $log->related_table = $table;
        $log->log_date      = now();
        $log->log_action    = $action;
        $log->log_remark    = $remark;
        $log->logger_type   = 'admin';
        $log->logged_by     = auth()->user() ? auth()->user()->user_id : 1;
        $log->save();
    }
}
