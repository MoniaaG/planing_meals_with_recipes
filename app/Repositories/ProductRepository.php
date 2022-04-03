<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Statements\ProductsFromAPI;
use Illuminate\Http\Request;
use OpenFoodFacts\Laravel\Facades\OpenFoodFacts;

class ProductRepository implements ProductRepositoryInterface
{
    public function create(Request $request)
    {
        $product = new Product();
        $product->barcode = null;
        $product->name = $request->name;
        $product->unit_id = $request->unit_id;
        $product->added = true;
        if($request->image) {
            $this->saveImage($request,$product);
        }
        $product->save();
        return $product;
    }

    public function update(Request $request, Product $product)
    {
        $product = Product::findOrFail($product->id); 
        $product->barcode = null;
        $product->name = $request->name;
        $product->unit_id = $request->unit_id;
        $product->added = true;
        if($request->image) {
            $this->saveImage($request,$product);
        }
        $product->save();
        return $product;
    }

    public function destroy(Product $product)
    {
        $product = Product::findOrFail($product->id);
        $product->delete();
    }

    private function saveImage(Request $request, Product $product) 
    {
        $file = $request->file('image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('/public/product/image', $filename);
        $product->image = '/storage/product/image/' . $filename;
    }

    public function propositionStore(Request $request){
        $proposition = new Product();
        $proposition->name = $request->name;
        $proposition->unit_id = $request->unit_id;
        $proposition->product_category_id = $request->product_category_id;
        $proposition->barcode = null;
        $proposition->added = 1; //oznacza to, że produkt jest proponowany przez użytkowników
        if($request->image) {
            $this->saveImage($request,$proposition);
        }
        $proposition->save();
        return $proposition;
    }

    public function searchProducts(Request $request) {
        $searchText = $request->search;
        if($searchText == "")
        {
            $productsFromDB = Product::where('name', 'like', '%' . $searchText . '%')->where('added', 0)->with('unit')->get();
            if(count($productsFromDB) > 0)
            {
                foreach($productsFromDB as $db){
                    $response[] = array(
                        'id' => $db['id'],
                        'text' => $db['name'],
                        'data-barcode' => $db['barcode'],
                        'data-unit' => $db['unit']['unit'],
                        'data-image' => $db['image'],
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
                        'data-image' => $db['image'],
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
                        'data-unit' => isset($api['quantity']) ? $api['quantity'] : null,
                        'data-image' => isset($api['image_front_small_url']) ? $api['image_front_small_url'] : 'image',
                    );
                }
            }
        }
        return response()->json($response);
    }

    public function saveProductsToRecipeOrPantry(Request $request, $recipe = null, $pantry = null, $edit = 0) {
        if(isset($recipe) && $edit) {
            $recipe->products()->detach();
        }
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
                    $newProduct->image = $product['image'];
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
        
            if(isset($pantry)) {
                $pantry->products()->attach($savedProduct->id, ['quantity' => $product['quantity']]);
            }
            if(isset($recipe)) {
                $recipe->products()->attach($savedProduct->id, ['quantity' => $product['quantity']]);
            }
        }
        if(isset($pantry)) {
            toastr()->success('Dodano produkty do spiżarni!');
        }
        return;
    }

}