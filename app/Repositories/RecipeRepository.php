<?php

namespace App\Repositories;

use App\Models\Recipe;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

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

    public function update(Recipe $recipe, Request $request) {
        $recipe = Recipe::findOrFail($recipe->id);
        $recipe->user_id = Auth::id();
        $request->share == true ? $recipe->share = true : $recipe->share = false;
        $recipe->description = $request->description;
        $recipe->short_description = $request->short_description;
        $recipe->name = $request->name;
        $recipe->category_id = $request->category_id;
        if($request->small_image) {
            //working system to delete existing file from storage and replace on new one
            /*$recipe1 = Recipe::find(Recipe::max('id'));
            Storage::delete((str_replace("/storage/", "/public/",$recipe1->small_image)));
            */
            //$recipe1 you could get to single line because you will work with the same object in next if
            //Storage::delete($recipe->small_image);
            $file = $request->file('small_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/public/recipe/small_image', $filename);
            $recipe->small_image = '/storage/recipe/small_image/' . $filename;
        }
        if($request->big_image) {
            //Storage::delete($recipe->big_image);
            $file = $request->file('big_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/public/recipe/big_image', $filename);
            $recipe->big_image = '/storage/recipe/big_image/' . $filename;
        }
        //$recipe->products()->sync($request->products)//ids
        $recipe->save();
        return $recipe;
    }

    public function search(Request $request) {
        // Wyszukiwanie w przepisach
        $recipes = Recipe::query();
        $worlds = explode(" ", $request->search);
        foreach ($worlds as $word) {
            $recipes->whereLike(['name','description', 'short_description'], $word);
        } 
        $recipes = $recipes->get();

        $array = array();
        array_push($array,$recipes);

        $newArray = array();
        for($i = 0; $i < count($array); $i++) {
            for($j = 0; $j < count($array[$i]); $j++) {
                array_push($newArray, $array[$i][$j]);
            }
        }
        $newArray = collect($newArray)->where('share',1);
        $newArray = (new Collection($newArray))->paginate(10);
        $newArray->appends(['search' => $request->search, 'per_page' => $request->per_page]);
        return $newArray;
    }
    
}