<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use OpenFoodFacts\Laravel\Facades\OpenFoodFacts;

class ProductController extends Controller
{
    public function index()
    {
        // zapytanie do bazy plus zapytanie do API

        $products = Product::all();
    }

    public function searchProducts(Request $request)
    {
        try {
                $searchText = $request->value;
                $productsFromAPI = OpenFoodFacts::find($searchText);
                $productsFromDB = Product::where('name', '=', $searchText)->get();
            return response()->json(['status' => 'success', 'productsFromAPI' => $productsFromAPI, 'productsFromDB' => $productsFromDB]);
        } catch (Exception $error) {
            return response()->json(['status' => 'fail'], 404);
        }
    }

    public function create()
    {
        return view('product.create');
    }

    
    public function store(Request $request)
    {
        $product = new Product();
        $product->barcode = null;
        $product->name = $request->name;
        $product->unit_id = $request->unit_id;
        $product->added = true;
        $product->save();
        //image needs to store in storage !!!!
        return redirect()->route('product.index');
    }

    public function edit(Product $product)
    {
        return view('product.edit', compact($product));
    }

    public function update(Product $product, Request $request)
    {
        $product = Product::findOrFail($product->id); 
        $product->barcode = null;
        $product->name = $request->name;
        $product->unit_id = $request->unit_id;
        $product->added = true;
        $product->save();
        return redirect()->route('product.update');
    }

    public function delete(Product $product)
    {
        $product = Product::findOrFail($product->id);
        $product->delete();
        return redirect()->route('product.index');
    }

}
