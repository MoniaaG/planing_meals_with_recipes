<?php

namespace App\Repositories;

use App\Models\ProductCategory;
use App\Models\Recipe;
use App\Repositories\Interfaces\ProductCategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    public function store(Request $request) {

    }

    public function update(Request $request, ProductCategory $productCategory) {
        
    }
}