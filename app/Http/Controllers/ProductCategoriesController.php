<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoriesController extends Controller
{
    public function index() {
        $product_categories = ProductCategory::all();
        return view('product_category.index',compact('product_categories'));
    }

    public function create(Request $request) {
        $product_category = new ProductCategory();
        $product_category->name = $request->name;
        $product_category->save();
        return redirect()->route('product_category.index');
    }

    public function edit(ProductCategory $product_category) {
        return $product_category;
    }

    public function update(ProductCategory $product_category, Request $request) {
        $product_category = ProductCategory::findOrFail($product_category->id);
        $product_category->name = $request->name;
        $product_category->save();
    }

    public function destroy(ProductCategory $product_category, Request $request) {
        //before deleting category add recipes from that to another !!!!
        try {
            dd($product_category->delete());
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $error) {
            return response()->json(['status' => 'fail'], 404);
        }
    }
}
