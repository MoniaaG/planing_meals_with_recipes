<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarRecipeRequest;
use App\Models\Calendar;
use App\Models\Pantry;
use App\Models\Recipe;
use App\Repositories\Interfaces\CalendarRepositoryInterface;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public $calendar;
    public $calendar_repository;

    public function __construct(Guard $auth, CalendarRepositoryInterface $calendar_repository)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->calendar = Calendar::where('owner_id', Auth::user()->id)->first();
            return $next($request);
        });   
        $this->calendar_repository = $calendar_repository;
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
            $this->calendar_repository->calendar_recipe_store($this->calendar, $request);
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

    public function delete_recipe(int $id)
    {
        try {
            $this->calendar->recipes()->wherePivot('id', $id)->detach();
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $error) {
            return response()->json(['status' => 'fail'], 404);
        }
    }

    public function assign_recipe(int $id)
    {
        try {
            $this->calendar_repository->assign_recipe($this->calendar, $id);
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $error) {
            return response()->json(['status' => 'fail'], 404);
        }
    }

    public function unsign_recipe(int $id){
        try {
            $this->calendar_repository->unsign_recipe($this->calendar, $id);
            return response()->json(['status' => 'success'], 200);
        }catch(Exception $error){
            return response()->json(['status' => 'fail'], 404);
        }

    }
}
