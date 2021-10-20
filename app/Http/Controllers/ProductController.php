<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Unit;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use OpenFoodFacts\Laravel\Facades\OpenFoodFacts;
class ProductController extends Controller
{
    public $product_repository;

    public function __construct(ProductRepositoryInterface $product_repository)
    {
        $this->product_repository = $product_repository;
    }
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
            $productsFromDB = Product::where('name', 'like', '%' . $searchText . '%')->with('unit')->get();
            $productFromDBBarcode = $productsFromDB->pluck('barcode');
            $array = [];
            $productsFromAPI = $productsFromAPI->whereNotIn('_id', $productFromDBBarcode);
            foreach($productsFromAPI as $api)
                $array[] = $api;
            $productsFromAPI = $array;
            return response()->json(['status' => 'success', 'productsFromAPI' => $productsFromAPI, 'productsFromDB' => $productsFromDB->count() == 0 ? null : $productsFromDB]);
        } catch (Exception $error) {
            return response()->json(['status' => 'fail'], 404);
        }
    }

    public function create()
    {
        $units = Unit::all();
        return view('product.create', compact('units'));
    }

    
    public function store(Request $request)
    {
        
        return redirect()->route('product.index');
    }

    public function edit(Product $product)
    {
        return view('product.edit', compact($product));
    }

    public function update(Product $product, Request $request)
    {
        $this->product_repository->update($request, $product);
        return redirect()->route('product.index');
    }

    public function delete(Product $product)
    {
        $this->product_repository->destroy($product);
        return redirect()->route('product.index');
    }

}
