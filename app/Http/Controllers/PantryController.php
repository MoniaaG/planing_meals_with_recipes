<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Pantry;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Models\User;
use App\Statements\ConstProductCategory;
use App\Statements\ProductsFromAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use OpenFoodFacts\Laravel\Facades\OpenFoodFacts;

class PantryController extends Controller
{
    
    public function index(User $owner)
    {
        $panrty = Pantry::where('owner_id', $owner->id);
        return redirect()->route('pantry');
    }

    public function addProductToPantryBlade()
    {
        return view('pantry.addProductsToPantry');
    }
 
    public function addProductToPantry(Request $request)
    {
        $pantry = Pantry::where('owner_id', Auth::id())->first();

        foreach($request->products as $product) {
            $savedProduct = $product;
            
            if($product['barcode'] != "null")
            {
                $productFromAPI = Product::where('barcode', $product['barcode'])->count();
                if(!$productFromAPI)
                {
                    $productBarcode = strtolower(strtok(ProductsFromAPI::getAPIProductCategory($product['barcode']), ','));
                    $newProduct = new Product();
                    $newProduct->name = $product['name'];
                    $newProduct->barcode = $product['barcode'];
                    $newProduct->image = 'image';
                    $newProduct->unit_id = Unit::where('unit', $product['unit_name'])->first()->id;
                    if(ProductCategory::where('name', 'like', $productBarcode)->count()) {
                        $newProduct->product_category_id = ProductCategory::where('name', 'like', $productBarcode)->first()->id;
                    }else {
                        $productCategory = ProductCategory::create([
                            'name' => $productBarcode,
                        ]);
                        $newProduct->product_category_id = $productCategory->id;
                    }
                    $newProduct->save();
                    $savedProduct = $newProduct;
                }else {
                    $savedProduct = Product::where('barcode', $product['barcode'])->first();
                }
            }else {
                $savedProduct = Product::findOrFail($product['id']);
            }
            $pantry->products()->attach($savedProduct->id, ['quantity' => $product['quantity'], 'expiration_date' => $product['expiration_date']]);

        }
        return redirect()->back();
    }

    public function whatNeedToBuy(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $user_calendar = Calendar::where('owner_id', Auth::id())->first();
        $recipes_between_dates = $user_calendar->recipes()->whereBetween('start_at', [$start, $end])->get();
        $array = [];
        foreach($recipes_between_dates as $recipe) {
            foreach($recipe->products()->get() as $product) {
                $array[] = $product;
            }
        }
        

        $array[0]->pivot;
        dd(collect($array)->where('id',1));//->where('attributes.id', 1)->get());

    }
}
