<?php

namespace App\Repositories;

use App\Models\Recipe;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeRepository implements RecipeRepositoryInterface
{
    public function searchRecipes(Request $request) {
        $response = [];
        $recipes = Recipe::where('user_id', Auth::id())->get();
        $liked_recipes = Auth::user()->liked_recipes();
        if(count($recipes))
            $recipes->concat($liked_recipes);
        else 
            $recipes = $liked_recipes;
        if($request->search != "")
            $recipes->where('name', 'like', '%' . $request->search . '%');
        
        foreach($recipes as $recipe){
            $response[] = array(
                'id' => $recipe['id'],
                'text' => $recipe['name'],
            );
        }
        return response()->json($response);
    }

    public function store(Request $request) {
        $recipe = new Recipe();
        $recipe->user_id = Auth::id();
        $request->share ? $recipe->share = true : $recipe->share = false;
        $recipe->description = $request->description;
        $recipe->short_description = $request->short_description;
        $recipe->name = $request->name;
        $recipe->category_id = $request->category_id;
        
        if($request->small_image) {
            $file = $request->file('small_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/public/recipe/small_image', $filename);
            $recipe->small_image = '/storage/recipe/small_image/' . $filename;
        }
        if($request->big_image) {
            $file = $request->file('big_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/public/recipe/big_image', $filename);
            $recipe->big_image = '/storage/recipe/big_image/' . $filename;
        }
        $recipe->save();
        return $recipe;
    }
    
}