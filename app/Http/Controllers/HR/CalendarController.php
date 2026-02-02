<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        // Logic to fetch events, leaves, holidays can be added here
        return view('hr.calendar.index');
    }
}
