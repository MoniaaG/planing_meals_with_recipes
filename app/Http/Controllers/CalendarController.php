<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function show()
    {
        $calendar = Calendar::where('owner_id', Auth::id())->get();
        //$recipes = $calendar->recipes;;
        return view('calendar.browse');
    }
}
