<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrDocument;
use App\Models\HrDocumentType;

class HrDocumentController extends Controller
{
    public function index(Request $request)
    {
        $typeId = $request->input('type_id');

        $query = HrDocument::with('type');

        if ($typeId) {
            $query->where('document_type_id', $typeId);
        }

        $documents = $query->orderBy('document_id', 'desc')->paginate(12);
        $types = HrDocumentType::all();

        return view('emp.documents.index', compact('documents', 'types', 'typeId'));
    }

    public function getData(Request $request)
    {
        $typeId = $request->input('type_id');
        $perPage = $request->input('per_page', 12);

        $query = HrDocument::with('type');

        if ($typeId) {
            $query->where('document_type_id', $typeId);
        }

        $documents = $query->orderBy('document_id', 'desc')->paginate($perPage);

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
}
