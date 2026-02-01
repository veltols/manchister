<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Eager load related entity for display
        $entity = null;
        if ($user->user_type == 'emp' || $user->user_type == 'hr' || $user->user_type == 'root') {
            $user->load('employee');
            $entity = $user->employee;
        } elseif ($user->user_type == 'atp') {
            $user->load('atp');
            $entity = $user->atp;
        }

        return view('dashboard.index', compact('user', 'entity'));
    }
}
