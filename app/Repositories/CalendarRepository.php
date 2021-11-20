<?php

namespace App\Repositories;

use App\Models\Calendar;
use App\Models\Pantry;
use App\Repositories\Interfaces\CalendarRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalendarRepository implements CalendarRepositoryInterface
{
    public function calendar_recipe_store(Calendar $calendar, Request $request) {
        $calendar->recipes()->attach($request->recipe_id, 
        [
            'start_at' => $request->start_at,
            'end_at' => $request->start_at,
            'text_color' => $request->text_color,
            'background_color' => $request->background_color
        ]);
    }

    public function assign_recipe(Calendar $calendar, int $id) {
        $pantry = Pantry::where('owner_id', Auth::id())->first();
        $recipe = $calendar->recipes()->wherePivot('id', $id)->first();
        $count = 0;
        foreach($recipe->products()->get() as  $product) {
            if($pantry->products()->where('product_id', $product->id)->get()->pluck('pivot.quantity')->sum() >= $product->pivot->quantity){
                $count++;
            }
        }
        foreach($recipe->products()->get() as  $product){
            if(count($recipe->products) ==  $count){
                $quantity = $product->pivot->quantity;
                foreach($pantry->products()->where('product_id', $product->id)->get() as $pantry_product) {
                    if(($pantry_product->pivot->quantity - $quantity) > 0){
                        $pantry->products()->wherePivot('id', $pantry_product->pivot->id)->update(['quantity' => ($pantry_product->pivot->quantity - $quantity)]);
                        $quantity = 0;
                    }else if(($pantry_product->pivot->quantity - $quantity) <= 0){
                        $quantity -= $pantry_product->pivot->quantity;
                        DB::table('pantry_product')->where('id', $pantry_product->pivot->id)->delete();
                    }else {
                        break;
                    }
                }
            }else {
                return response()->json(['status' => 'fail'], 404);
            }
        }
        $calendar->recipes()->wherePivot('id', $id)->update(['cooked' => true]);
    }

    public function unsign_recipe(Calendar $calendar, int $id) {
        $recipe = $calendar->recipes()->wherePivot('id', $id)->first();
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
        $calendar->recipes()->wherePivot('id', $id)->update(['cooked' => false]);
    }
}