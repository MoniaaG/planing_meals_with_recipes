<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Http\Request;

interface ProductRepositoryInterface
{
    public function create(Request $request);

    public function update(Request $request, Product $product);

    public function destroy(Product $product);

}