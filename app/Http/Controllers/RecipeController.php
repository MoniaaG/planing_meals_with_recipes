<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $categories = Category::all();
        return view('recipe.create', compact('categories'));
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
        //$recipe->products()->attach($request->products);//ids
        $recipe->save();
        dd($request, $recipe->small_image);
        if($request->product != null) {
            foreach($request->product as $key => $product) {
                $product = Product::findOrFail($product[$key]['id']);
                
            }
        }
        return redirect()->route('recipe.index');

    }

    public function edit(Recipe $recipe) {
        return view('recipe.edit', compact('recipe'));
    }

    public function update(Recipe $recipe, Request $request) {
        $recipe = Recipe::findOrFail($recipe->id);
        $recipe->user_id = Auth::id();
        $request->share ? $recipe->share = true : $recipe->share = false;
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
            Storage::delete($recipe->small_image);
            $file = $request->file('small_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/public/recipe/small_image', $filename);
            $recipe->small_image = '/storage/recipe/small_image/' . $filename;
        }
        if($request->big_image) {
            Storage::delete($recipe->big_image);
            $file = $request->file('big_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/public/recipe/big_image', $filename);
            $recipe->big_image = '/storage/recipe/big_image/' . $filename;
        }
        //$recipe->products()->sync($request->products)//ids
        $recipe->save();
    }

    public function delete(Recipe $recipe) {
        //$user->products()->detach();
        $recipe->delete();
    }
}
