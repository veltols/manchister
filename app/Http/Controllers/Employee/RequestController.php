<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\HrDocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index()
    {
       $documentTypes = HrDocumentType::with('documents')->get();
        return view('emp.requests.index', compact('documentTypes'));
    }
}
