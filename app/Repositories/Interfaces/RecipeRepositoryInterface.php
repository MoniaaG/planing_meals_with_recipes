<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface RecipeRepositoryInterface
{
    public function searchRecipes(Request $request);

    public function store(Request $request);

    public function update(Recipe $recipe, Request $request);

    public function search(Request $request);
}
