<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarRecipeRequest;
use App\Models\Calendar;
use App\Models\Pantry;
use App\Models\Recipe;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $pantry = Pantry::where('owner_id', Auth::id())->first();
            $recipe = $this->calendar->recipes()->wherePivot('id', $id)->first();
            $count = 0;
            foreach($recipe->products()->get() as  $product) {
                if($pantry->products()->where('product_id', $product->id)->get()->pluck('pivot.quantity')->sum() >= $product->pivot->quantity){
                    $count++;
                }
            }
            foreach($recipe->products()->get() as  $product)
            {
                $quantity = $product->pivot->quantity;
            if(count($recipe->products) ==  $count){
                foreach($pantry->products()->where('product_id', $product->id)->get() as $pantry_product) {
                    if(($pantry_product->pivot->quantity - $quantity) > 0)
                    {
                        $pantry->products()->wherePivot('id', $pantry_product->pivot->id)->update(['quantity' => ($pantry_product->pivot->quantity - $quantity)]);
                        $quantity = 0;
                    }else if(($pantry_product->pivot->quantity - $quantity) == 0) { 
                        $pantry->products()->wherePivot('id', $pantry_product->pivot->id)->delete();
                        $quantity = 0;
                    }else {
                        if($quantity > 0) {
                            if($pantry_product->pivot->quantity - $quantity > 0){
                                $pantry->products()->wherePivot('id', $pantry_product->pivot->id)->update(['quantity' => ($pantry_product->pivot->quantity - $quantity)]);
                                $quantity = 0;
                            }else if($pantry_product->pivot->quantity - $quantity <= 0){
                                $quantity -= $pantry_product->pivot->quantity;
                                $pantry->products()->wherePivot('id', $pantry_product->pivot->id)->delete();
                            }else {
                                break;
                            }
                        }
                    }
                }
            }else {
                return response()->json(['status' => 'fail'], 404);
            }
        }
        $this->calendar->recipes()->wherePivot('id', $id)->update(['cooked' => true]);
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $error) {
            return response()->json(['status' => 'fail'], 404);
        }
    }

    public function unsign_recipe(int $id){
        try {
            $recipe = $this->calendar->recipes()->wherePivot('id', $id)->first();
            $pantry = Pantry::where('owner_id', Auth::id())->first();
            foreach($recipe->products as $product) {
                if($pantry->products()->where('product_id', $product->id)->first() != null){
                    $pantry_product_id = $pantry->products()->where('product_id', $product->id)->first()->pivot->id;
                    $pantry_product = $pantry->products()->wherePivot('id',$pantry_product_id)->first();
                    $pantry->products()->wherePivot('id',$pantry_product_id)->update(['quantity' => ($pantry_product->pivot->quantity + $product->pivot->quantity)]);
                }else {
                    $pantry->products()->attach($product->id, ['quantity' => $product->pivot->quantity]);
                }
            }
            $this->calendar->recipes()->wherePivot('id', $id)->update(['cooked' => false]);
            return response()->json(['status' => 'success'], 200);
        }catch(Exception $error){
            return response()->json(['status' => 'fail'], 404);
        }

    }
}
