<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    public function show(Recipe $recipe) {
        return $recipe;
    }

    public function index() {
        $recipes = Recipe::all();
        return view('recipe.indexAll', compact($recipes));
    }

    public function create()
    {
        return view('recipe.create');
    }
    public function store(Request $request) {
        $recipe = new Recipe();
        $recipe->user_id = Auth::id();
        $recipe->share = $request->share;
        $recipe->description = $request->description;
        $recipe->short_description = $request->short_description;
        $recipe->name = $request->name;
        $recipe->category_id = $request->category_id;
        $recipe->small_image = $request->small_image;
        $recipe->big_image = $request->big_image;
        // needs to connect with storage "small/big_image"
        //$recipe->products()->attach($request->products);//ids
        $recipe->save();
        return redirect()->route('recipe.index');

    }

    public function edit(Recipe $recipe) {
        return view('recipe.edit', compact('recipe'));
    }

    public function update(Recipe $recipe, Request $request) {
        $recipe = Recipe::findOrFail($recipe->id);
        $recipe->user_id = Auth::id();
        $recipe->share = $request->share;
        $recipe->description = $request->description;
        $recipe->short_description = $request->short_description;
        $recipe->name = $request->name;
        $recipe->category_id = $request->category_id;
        $recipe->small_image = $request->small_image;
        $recipe->big_image = $request->big_image;
        // needs to connect with storage "small/big_image"
        //$recipe->products()->sync($request->products)//ids
        $recipe->save();
    }

    public function delete(Recipe $recipe) {
        //$user->products()->detach();
        $recipe->delete();
    }
}
