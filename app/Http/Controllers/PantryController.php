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
 
    public function addProductToPantry(Product $product, Pantry $pantry)
    {
        $pantry = Pantry::findOrFail($pantry->id);
        $pantry->products()->attach($product->id);
    }
}
