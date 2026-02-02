<?php

namespace App\Http\Controllers\Rc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Atp;
use App\Models\AtpStatus;
use Illuminate\Support\Facades\DB;

class AtpController extends Controller
{
    public function index(Request $request)
    {
        $statusId = $request->input('status_id');
        $search = $request->input('search');

        $query = Atp::with(['status', 'addedBy']);

        if ($statusId) {
            $query->where('atp_status_id', $statusId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('atp_name', 'like', "%{$search}%")
                    ->orWhere('atp_email', 'like', "%{$search}%")
                    ->orWhere('atp_phone', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%");
            });
        }

        $atps = $query->orderBy('atp_id', 'desc')->paginate(10);
        $statuses = AtpStatus::all();

        return view('rc.atps.index', compact('atps', 'statuses', 'statusId', 'search'));
    }

    public function show($id)
    {
        $atp = Atp::with(['status', 'addedBy', 'logs'])->findOrFail($id);
        return view('rc.atps.show', compact('atp'));
    }
}
