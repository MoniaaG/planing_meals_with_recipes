<?php

namespace App\Http\Controllers;

use App\Models\Pantry;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class PantryController extends Controller
{
    public function index(User $owner)
    {
        $panrty = Pantry::where('owner_id', $owner->id);
        return redirect()->route('pantry');
    }
 
    public function addProductToPantry(Product $product, Pantry $pantry, Request $request)
    {
        $pantry = Pantry::findOrFail($pantry->id);
        for($i = 0; $i < count($request->product_id); $i++){
            $product = $pantry->products()->attach($request->product_id[$i]);
            $product->quantity = $request->product_id[$i][1];
        }
    }
}
