<?php

namespace App\Repositories\Interfaces;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

interface ProductCategoryRepositoryInterface
{
    public function store(Request $request);

    public function update(Request $request, ProductCategory $productCategory);
}