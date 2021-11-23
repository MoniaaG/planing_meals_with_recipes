<?php

namespace App\Repositories\Interfaces;

use App\Models\Pantry;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\Request;

interface ProductRepositoryInterface
{
    public function create(Request $request);

    public function update(Request $request, Product $product);

    public function destroy(Product $product);

    public function propositionStore(Request $request);

    public function searchProducts(Request $request);

    public function saveProductsToRecipeOrPantry(Request $request, $recipe = null,$pantry = null, $edit = 0);
}