<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;

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
}