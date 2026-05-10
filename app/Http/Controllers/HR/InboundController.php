<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\InboundCorrespondence;
use App\Models\InboundExternalEntity;
use App\Models\InboundAttachment;
use App\Models\AppSetting;
use App\Models\User;

/**
 * HR / Liaison Officer Controller for Inbound Correspondence
 * Only users with is_liaison = 1 can access this module.
 * Handles Form A: Register, classify and list inbound correspondences.
 */
class InboundController extends Controller
{
    /**
     * Guard: Ensure only Liaison Officers can access this module.
     */
    protected function ensureLiaison()
    {
        $user = Auth::user();
        if (!$user || !$user->is_liaison) {
            abort(403, 'Access denied. Only designated Liaison Officers can access this module.');
        }
    }

    // ── Index: List inbound correspondences for this Liaison Officer ──────────
    public function index(Request $request)
    {
        $this->ensureLiaison();

        $query = InboundCorrespondence::with(['entity', 'registeredBy', 'attachments', 'actionItems'])
            ->where('registered_by', Auth::user()->user_id) // Only show their own submissions
            ->orderBy('inbound_id', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('reference_code', 'like', "%$s%")
                  ->orWhere('subject', 'like', "%$s%");
            });
        }

        $records  = $query->paginate(15);
        $entities = InboundExternalEntity::where('is_active', 1)->orderBy('entity_name')->get();

        return view('hr.inbound.index', compact('records', 'entities'));
    }

    // ── getData: AJAX paginated data ──────────────────────────────────────────
    public function getData(Request $request)
    {
        $this->ensureLiaison();

        $perPage = $request->get('per_page', 15);
        $query   = InboundCorrespondence::with(['entity', 'registeredBy', 'actionItems'])
            ->where('registered_by', Auth::user()->user_id)
            ->orderBy('inbound_id', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $records->items(),
            'pagination' => [
                'current_page' => $records->currentPage(),
                'last_page'    => $records->lastPage(),
                'per_page'     => $records->perPage(),
                'total'        => $records->total(),
                'from'         => $records->firstItem(),
                'to'           => $records->lastItem(),
            ],
        ]);
    }

    // ── show: Form A detail view ──────────────────────────────────────────────
    public function show($id)
    {
        $this->ensureLiaison();

        $record = InboundCorrespondence::with([
            'entity', 'registeredBy', 'attachments', 'actionItems.assignedTo',
        ])->where('registered_by', Auth::user()->user_id)->findOrFail($id);

        $entities = InboundExternalEntity::where('is_active', 1)->orderBy('entity_name')->get();

        return view('hr.inbound.show', compact('record', 'entities'));
    }

    // ── store: Save Form A (Liaison Officer creates record) ───────────────────
    public function store(Request $request)
    {
        $this->ensureLiaison();

        $request->validate([
            'entity_id'             => 'required|exists:inbound_external_entities,entity_id',
            'date_of_receipt'       => 'required|date',
            'priority'              => 'required|in:Low,Medium,High',
            'confidentiality_level' => 'required|in:Open,Confidential,Restricted',
            'mode_of_receipt'       => 'required|in:Hard Copy,Email,Flash Drive,Scanned Copy,Email Attachment,Other',
            'subject'               => 'required|string|max:500',
            'purpose'               => 'required|string',
            'attachments.*'         => 'nullable|file|max:20480',
        ]);

        $entity = InboundExternalEntity::findOrFail($request->entity_id);

        // Auto-find the GM user_id
        $gmUserId = User::where('is_gm', 1)->value('user_id');

        $record = InboundCorrespondence::create([
            'reference_code'        => InboundCorrespondence::generateReferenceCode($entity),
            'correspondence_type'   => 'inbound',
            'entity_id'             => $request->entity_id,
            'date_of_receipt'       => $request->date_of_receipt,
            'priority'              => $request->priority,
            'confidentiality_level' => $request->confidentiality_level,
            'mode_of_receipt'       => $request->mode_of_receipt,
            'subject'               => $request->subject,
            'description'           => $request->description,
            'purpose'               => $request->purpose,
            'status'                => 'Pending Approval',
            'registered_by'         => Auth::user()->user_id, // user_id from users_list
            'gm_user_id'            => $gmUserId,  // auto-routed to current GM
        ]);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('inbound_attachments', 'public');
                InboundAttachment::create([
                    'inbound_id'  => $record->inbound_id,
                    'file_name'   => $file->getClientOriginalName(),
                    'file_path'   => $path,
                    'file_type'   => $file->getClientMimeType(),
                    'uploaded_by' => Auth::user()->user_id, // user_id
                ]);
            }
        }

        return redirect()->route('hr.inbound.show', $record->inbound_id)
            ->with('success', "Inbound correspondence registered. Reference: {$record->reference_code}. Sent to GM for review.");
    }

    // ── update: Resubmit Form A after GM requests modifications ───────────────────
    public function update(Request $request, $id)
    {
        $this->ensureLiaison();

        $record = InboundCorrespondence::where('registered_by', Auth::user()->user_id)->findOrFail($id);

        if ($record->status !== 'Modifications Required') {
            abort(403, 'Only correspondence requiring modifications can be updated.');
        }

        $request->validate([
            'entity_id'             => 'required|exists:inbound_external_entities,entity_id',
            'date_of_receipt'       => 'required|date',
            'priority'              => 'required|in:Low,Medium,High',
            'confidentiality_level' => 'required|in:Open,Confidential,Restricted',
            'mode_of_receipt'       => 'required|in:Hard Copy,Email,Flash Drive,Scanned Copy,Email Attachment,Other',
            'subject'               => 'required|string|max:500',
            'description'           => 'nullable|string',
            'purpose'               => 'nullable|string',
            'attachments.*'         => 'nullable|file|mimes:pdf,doc,docx,jpg,png,jpeg|max:20480',
        ]);

        $record->update([
            'entity_id'             => $request->entity_id,
            'date_of_receipt'       => $request->date_of_receipt,
            'priority'              => $request->priority,
            'confidentiality_level' => $request->confidentiality_level,
            'mode_of_receipt'       => $request->mode_of_receipt,
            'subject'               => $request->subject,
            'description'           => $request->description,
            'purpose'               => $request->purpose,
            'status'                => 'Resubmitted', // Instead of 'Pending Approval'
            'gm_comments'           => null,          // Clear previous GM comments
        ]);

        // Handle new attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('inbound_attachments', 'public');
                InboundAttachment::create([
                    'inbound_id'  => $record->inbound_id,
                    'file_name'   => $file->getClientOriginalName(),
                    'file_path'   => $path,
                    'file_type'   => $file->getClientMimeType(),
                    'uploaded_by' => Auth::user()->user_id,
                ]);
            }
        }

        return redirect()->route('hr.inbound.show', $record->inbound_id)
            ->with('success', 'Correspondence successfully modified and resubmitted to the GM.');
    }

    // ── Entities Management ───────────────────────────────────────────────────
    public function storeEntity(Request $request)
    {
        $this->ensureLiaison();

        $request->validate([
            'entity_name'  => 'required|string|max:200',
            'entity_code'  => 'required|string|max:10|unique:inbound_external_entities,entity_code',
            'entity_email' => 'nullable|email|max:200',
            'entity_phone' => 'nullable|string|max:50',
        ]);

        InboundExternalEntity::create($request->only('entity_name', 'entity_code', 'entity_email', 'entity_phone'));

        return redirect()->back()->with('success', 'External entity added successfully.');
    }
}
