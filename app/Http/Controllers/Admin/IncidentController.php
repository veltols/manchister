<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\IncidentType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\SystemLog;

class IncidentController extends Controller
{
    public function index()
    {
        $incidents = Incident::with('reporter.employee')->latest()->paginate(10);
        $types = IncidentType::orderBy('type_name')->get();
        return view('admin.incidents.index', compact('incidents', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'incident_date' => 'required|date',
            'incident_type' => 'required|string',
            'description'   => 'required|string',
            'attachment'    => 'nullable|file|max:10240',
        ]);

        $incident = new Incident();
        $incident->incident_date = $request->incident_date;
        $incident->incident_type = $request->incident_type;
        $incident->description   = $request->description;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = 'incident_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/incidents'), $filename);
            $incident->attachment = 'uploads/incidents/' . $filename;
        }

        $incident->reported_by = Auth::id();
        $incident->save();

        $this->logAction($incident->incident_id, 'Incident Created', "New incident reported: " . $incident->incident_type);

        return redirect()->back()->with('success', 'Incident recorded successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'incident_date' => 'required|date',
            'incident_type' => 'required|string',
            'description'   => 'required|string',
            'attachment'    => 'nullable|file|max:10240',
        ]);

        $incident = Incident::findOrFail($id);
        $incident->incident_date = $request->incident_date;
        $incident->incident_type = $request->incident_type;
        $incident->description   = $request->description;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = 'incident_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/incidents'), $filename);
            $incident->attachment = 'uploads/incidents/' . $filename;
        }

        $incident->save();

        $this->logAction($incident->incident_id, 'Incident Updated', "Incident updated: " . $incident->incident_type);

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
        $date    = $request->get('date');       // YYYY-MM-DD
        $reporter = $request->get('reporter');   // name search

        $query = Incident::with('reporter.employee')->latest('incident_date');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('incident_type', 'like', "%{$search}%");
            });
        }

        // Filter by date
        if ($date) {
            $query->whereDate('incident_date', $date);
        }

        // Filter by reporter name
        if ($reporter) {
            $query->where(function ($q) use ($reporter) {
                $q->whereHas('reporter.employee', function ($sq) use ($reporter) {
                    $sq->where('first_name', 'like', "%{$reporter}%")
                       ->orWhere('last_name', 'like', "%{$reporter}%");
                })->orWhereHas('reporter', function ($sq) use ($reporter) {
                    $sq->where('user_email', 'like', "%{$reporter}%");
                });
            });
        }

        $incidents = $query->paginate($perPage);

        // Transform data
        $incidents->getCollection()->transform(function ($incident) {
            $reporterName = optional(optional($incident->reporter)->employee)->first_name
                ?? optional($incident->reporter)->user_email
                ?? 'System';
            $incident->reporter_name   = $reporterName;
            $incident->formatted_date  = \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y');
            $incident->formatted_time  = \Carbon\Carbon::parse($incident->incident_date)->format('h:i A');
            $incident->raw_date        = \Carbon\Carbon::parse($incident->incident_date)->format('Y-m-d\TH:i');
            $incident->attachment_url  = $incident->attachment ? asset($incident->attachment) : null;
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
