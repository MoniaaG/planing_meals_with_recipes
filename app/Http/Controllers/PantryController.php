<?php

namespace App\Http\Controllers;

use App\Models\Pantry;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Models\User;
use App\Statements\ConstProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                    $productBarcode = strtolower(strtok(isset(OpenFoodFacts::barcode($product['barcode'])['categories']) ? OpenFoodFacts::barcode($product['barcode'])['categories'] : ConstProductCategory::constProductCategory(), ','));
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
                    dump($newProduct);
                    $savedProduct = $newProduct;
                }else {
                    $savedProduct = Product::where('barcode', $product['barcode'])->first();
                }
            }else {
                $savedProduct = Product::findOrFail($product['id']);
            }
            $pantry->products()->attach($savedProduct->id, ['quantity' => $product['quantity']]);

        }
        return redirect()->back();


        /*for($i = 0; $i < count($request->product_id); $i++){
            $product = $pantry->products()->attach($request->product_id[$i]);
            $product->quantity = $request->product_id[$i][1];
        }*/
    }
}
