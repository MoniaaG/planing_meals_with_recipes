<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarRecipeRequest;
use App\Models\Calendar;
use App\Models\Recipe;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public $calendar;

    public function __construct(Guard $auth)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->calendar = Calendar::where('owner_id', Auth::user()->id)->first();
            return $next($request);
        });   
    }
    

    public function show()
    {
        $recipes = Recipe::all();
        return view('calendar.browse', compact('recipes'));
    }

    public function index()
    {
        $events = $this->calendar->recipes_events();
        return response()->json($events, 200);
    }

    public function calendar_recipe_store(CalendarRecipeRequest $request)
    {
        if(isset($this->calendar)) {
            $this->calendar->recipes()->attach($request->recipe_id, 
            [
                'start_at' => $request->start_at,
                'end_at' => $request->start_at,
                'text_color' => $request->text_color,
                'background_color' => $request->background_color
            ]);
        }else {
            //send error message 
        }
        return redirect()->back();
    }

    public function calendar_day(Request $request)
    {
        $events = $this->calendar->recipes_events_day($request->start);
        return response()->json($events);
    }

    public function delete_recipe(Int $id)
    {
        $this->calendar->recipes()->detach($id);
        return redirect()->back();
    }
}
