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
            $searchText = $request->search;
            if($searchText == "")
            {
                $productsFromDB = Product::where('name', 'like', '%' . $searchText . '%')->with('unit')->get();
                if(count($productsFromDB) > 0)
                {
                    foreach($productsFromDB as $db){
                        $response[] = array(
                            'id' => $db['id'],
                            'text' => $db['name'],
                            'data-barcode' => $db['barcode'],
                            'data-unit' => $db['unit']['unit'],
                        );
                    }
                }
            }else {
                $productsFromAPI = OpenFoodFacts::find($searchText);
                $productsFromDB = Product::where('name', 'like', '%' . $searchText . '%')->with('unit')->get();
                $productFromDBBarcode = $productsFromDB->pluck('barcode');
                $response = [];
                $productsFromAPI = $productsFromAPI->whereNotIn('_id', $productFromDBBarcode);
                if(count($productsFromDB) > 0)
                {
                    foreach($productsFromDB as $db){
                        $response[] = array(
                            'id' => $db['id'],
                            'text' => $db['name'],
                            'data-barcode' => $db['barcode'],
                            'data-unit' => $db['unit']['unit'],
                        );
                    }
                }
                if(count($productsFromAPI) > 0)
                {
                    foreach($productsFromAPI as $api){
                        $response[] = array(
                            'id' => $api['_id'],
                            'text' => isset($api['product_name']) ? $api['product_name'] : $searchText,
                            'data-barcode' => $api['_id'],
                            'data-unit' => isset($api['quantity']) ? $api['quantity'] : null
                        );
                    }
                }
            }
            return response()->json($response);
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
