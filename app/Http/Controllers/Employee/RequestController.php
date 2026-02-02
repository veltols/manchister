<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index()
    {
        $documentTypes = DB::table('hr_documents_types')->get();
        return view('emp.requests.index', compact('documentTypes'));
    }
}
