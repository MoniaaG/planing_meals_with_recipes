<?php

namespace App\Http\Controllers;

use App\Models\Pantry;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function proposition_create()
    {
        $units = Unit::all();
        $product_categories = ProductCategory::all();
        return view('product.product_proposition', compact('units', 'product_categories'));
    }

    public function proposition_store(Request $request)
    {
        try{
            $proposition = new Product();
            $proposition->name = $request->name;
            $proposition->unit_id = $request->unit_id;
            $proposition->product_category_id = $request->product_category_id;
            $proposition->image = 'image';
            $proposition->barcode = null;
            $proposition->added = 1; //oznacza to, że produkt jest proponowany przez użytkowników
            $proposition->save();
            toastr()->success('Podano produkt do proponowanych!');
        }catch (Exception $error) {
            toastr()->error('Próba dodania propozycji produktu nie powiodła się. Spróbuj ponownie!');
            return back();
        }
        return redirect()->route('homepage');
    }
}
