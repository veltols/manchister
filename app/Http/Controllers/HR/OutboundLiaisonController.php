<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommunicationRequest;
use App\Models\CommunicationAttachment;
use App\Models\SystemLog;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OutboundLiaisonController extends Controller
{
    protected function ensureLiaison()
    {
        $user = Auth::user();
        if (!$user || !$user->is_liaison) {
            abort(403, 'Access denied. Only Liaison Officers can access this module.');
        }
        return $user->employee->employee_id ?? $user->user_id;
    }

    public function index(Request $request)
    {
        $this->ensureLiaison();

        $query = CommunicationRequest::with(['employee', 'type', 'status'])
            ->where('is_approved_2', 1) // Approved by GM
            ->orderBy('approved_2_date', 'desc');

        if ($request->filled('status')) {
            $query->where('communication_status_id', $request->status);
        } else {
            $query->where('communication_status_id', 3); // Default to "Ready for Liaison"
        }

        $records = $query->paginate(15);

        return view('hr.communications.outbound_liaison.index', compact('records'));
    }

    public function finalize(Request $request, $id)
    {
        $liaisonId = $this->ensureLiaison();

        $request->validate([
            'final_file' => 'required|file|mimes:pdf,jpg,png,docx|max:10240',
            'external_party_code' => 'required|string|max:50', // Entity code for ref
        ]);

        $record = CommunicationRequest::findOrFail($id);

        // 1. Generate Reference Code: [ENTITY] / [YYYY] / [MM] / OUT / [SEQ]
        $year = date('Y');
        $month = date('m');
        $entityCode = strtoupper($request->external_party_code);
        
        // Find next sequence for this entity in this year
        $lastRef = DB::table('m_communications_list')
            ->where('communication_code', 'LIKE', "{$entityCode}/{$year}/%")
            ->orderBy('communication_id', 'desc')
            ->first();

        $seq = 1;
        if ($lastRef) {
            $parts = explode('/', $lastRef->communication_code);
            $lastSeq = (int) end($parts);
            $seq = $lastSeq + 1;
        }
        $refCode = "{$entityCode}/{$year}/{$month}/OUT/" . str_pad($seq, 4, '0', STR_PAD_LEFT);

        // 2. Handle Final File Upload
        if ($request->hasFile('final_file')) {
            $file = $request->file('final_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('communications/outbound/final', $fileName, 'public');

            CommunicationAttachment::create([
                'communication_id' => $record->communication_id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'uploaded_by' => $liaisonId,
                'is_final' => 1,
            ]);
        }

        // 3. Update Record
        $record->communication_code = $refCode;
        $record->communication_status_id = 4; // Completed / Dispatched
        $record->save();

        SystemLog::create([
            'related_table' => 'm_communications_list',
            'related_id' => $record->communication_id,
            'log_action' => 'Liaison_Finalized',
            'log_remark' => 'Liaison finalized dispatch. Ref Code generated: ' . $refCode,
            'log_date' => now(),
            'logged_by' => $liaisonId,
            'logger_type' => 'employees_list',
            'log_type' => 'int',
        ]);

        NotificationService::send(
            "Your Outbound Comm REF: " . $refCode . " has been finalized and dispatched.",
            "emp/communications/show/" . $record->communication_id,
            $record->requested_by
        );

        return redirect()->route('hr.communications.outbound_liaison.index')
            ->with('success', 'Communication finalized with Ref: ' . $refCode);
    }
}
