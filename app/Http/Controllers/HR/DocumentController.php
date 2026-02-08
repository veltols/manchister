<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrDocument;
use App\Models\HrDocumentType;
use Illuminate\Support\Facades\Storage; // Note: We might need custom move if not using Storage disk
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = HrDocument::with('type')->orderBy('document_id', 'desc');

        if ($request->has('type_id') && $request->type_id != '') {
             $query->where('document_type_id', $request->type_id);
        }

        $documents = $query->paginate(15);
        $types = HrDocumentType::all();

        return view('hr.documents.index', compact('documents', 'types'));
    }

    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $query = HrDocument::with('type')->orderBy('document_id', 'desc');

        if ($request->has('type_id') && $request->type_id != '') {
             $query->where('document_type_id', $request->type_id);
        }

        $documents = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $documents->items(),
            'pagination' => [
                'current_page' => $documents->currentPage(),
                'last_page' => $documents->lastPage(),
                'per_page' => $documents->perPage(),
                'total' => $documents->total(),
                'from' => $documents->firstItem(),
                'to' => $documents->lastItem(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_title' => 'required|string|max:255',
            'document_type_id' => 'required|exists:hr_documents_types,document_type_id',
            'document_attachment' => 'required|file|max:10240', // 10MB max
        ]);

        $doc = new HrDocument();
        $doc->document_title = $request->document_title;
        $doc->document_description = $request->document_description ?? '';
        $doc->document_type_id = $request->document_type_id;

        if ($request->hasFile('document_attachment')) {
            $file = $request->file('document_attachment');
            // Legacy system stores in 'uploads/' adjacent to app? 
            // We should stick to 'public/uploads' if possible or whatever the legacy path expects if we want compatibility.
            // Based on legacy view: `../uploads/` -> which implies `manchster_php/uploads`.
            // For Laravel, we usually use `storage/app/public`.
            // Let's store in `public/uploads` to be safe and accessible.
            
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $doc->document_attachment = $filename;
        }

        $doc->save();

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }
    
    public function destroy($id)
    {
        $doc = HrDocument::findOrFail($id);
        // Optional: Delete file from storage
        if($doc->document_attachment && file_exists(public_path('uploads/' . $doc->document_attachment))){
            unlink(public_path('uploads/' . $doc->document_attachment));
        }
        $doc->delete();
        
        return redirect()->back()->with('success', 'Document deleted.');
    }
}
