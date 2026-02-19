<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Incident;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class IncidentController extends Controller
{
    public function index()
    {
        $incidents = Incident::latest()->paginate(10);
        return view('admin.incidents.index', compact('incidents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'incident_date' => 'required|date',
            'incident_type' => 'required|string',
            'description' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB
        ]);

        $incident = new Incident();
        $incident->incident_date = $request->incident_date;
        $incident->incident_type = $request->incident_type;
        $incident->description = $request->description;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = 'incident_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/incidents'), $filename);
            $incident->attachment = 'uploads/incidents/' . $filename;
        }

        $incident->reported_by = Auth::id();
        $incident->save();

        return redirect()->back()->with('success', 'Incident recorded successfully.');
    }

    public function show($id)
    {
        $incident = Incident::findOrFail($id);
        return view('admin.incidents.show', compact('incident'));
    }

    public function getData(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');

        $query = Incident::with('reporter.employee')
            ->latest('incident_date');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('incident_type', 'like', "%{$search}%");
            });
        }

        $incidents = $query->paginate($perPage);

        // Transform data
        $incidents->getCollection()->transform(function ($incident) {
            $reporterName = optional(optional($incident->reporter)->employee)->first_name
                ?? optional($incident->reporter)->user_email
                ?? 'System';
            $incident->reporter_name = $reporterName;
            $incident->formatted_date = \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y');
            $incident->formatted_time = \Carbon\Carbon::parse($incident->incident_date)->format('h:i A');
            $incident->attachment_url = $incident->attachment ? asset($incident->attachment) : null;
            return $incident;
        });

        return response()->json([
            'success' => true,
            'data' => $incidents->items(),
            'pagination' => [
                'current_page' => $incidents->currentPage(),
                'last_page' => $incidents->lastPage(),
                'per_page' => $incidents->perPage(),
                'total' => $incidents->total(),
                'from' => $incidents->firstItem(),
                'to' => $incidents->lastItem(),
            ]
        ]);
    }
}
