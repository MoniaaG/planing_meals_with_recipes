<?php

namespace App\Repositories;

use App\Models\ProductCategory;
use App\Repositories\Interfaces\ProductCategoryRepositoryInterface;
use Illuminate\Http\Request;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    public function store(Request $request) {
        $product_category = new ProductCategory();
        $product_category->name = $request->name;
        $product_category->save();
    }

    public function update(Request $request, ProductCategory $productCategory) {
        $product_category = ProductCategory::findOrFail($productCategory->id);
        $product_category->name = $request->name;
        $product_category->save();
    }
}