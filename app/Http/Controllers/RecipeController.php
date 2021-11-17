<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddRecipeRequest;
use App\Models\Category;
use App\Models\Like;
use App\Models\Opinion;
use App\Models\Pantry;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Recipe;
use App\Models\Unit;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use OpenFoodFacts\Laravel\Facades\OpenFoodFacts;
use App\Statements\ConstProductCategory;
use App\Statements\ProductsFromAPI;
use Exception;

class RecipeController extends Controller
{
    public $recipe_repository;
    public $product_repository;

    public function __construct(RecipeRepositoryInterface $recipe_repository, ProductRepositoryInterface $product_repository)
    {
        $this->recipe_repository = $recipe_repository;
        $this->product_repository = $product_repository;
    }

    public function show(Recipe $recipe) {
        $opinion = Opinion::where([['creator_id', Auth::id()], ['recipe_id', $recipe->id]])->first();
        $recipe = $recipe->where('id', $recipe->id)->with('products.unit')->first();
        return view('recipe.show', compact('recipe', 'opinion'));
    }

    public function index() {
        $recipes = Recipe::where('user_id', Auth::id())->get();
        return view('recipe.indexAll', compact('recipes'));
    }

    public function searchRecipes(Request $request) {
        return $this->recipe_repository->searchRecipes(($request));
    }
    
    public function create()
    {
        $categories = Category::all();
        return view('recipe.create', compact('categories'));
    }
    public function store(AddRecipeRequest $request) {
        $recipe = $this->recipe_repository->store($request);
        $this->product_repository->saveProductsToRecipeOrPantry($request, $recipe,null);
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

    public function like(Recipe $recipe, Request $request) {
        try {
            if($recipe->liked()->count() == 0)
            {
                Like::create(['user_id' => Auth::id(), 'recipe_id' => $recipe->id]);
            }else {
                $recipe->liked()->first()->delete();
            }
            return response()->json(['status' => 'success'], 200);
        }catch(Exception $error){
            return response()->json(['status' => 'fail'], 404);
        }
    }

    public function opinionAdd(Request $request, Recipe $recipe){
        try {
            if(Opinion::where([['creator_id', Auth::id()], ['recipe_id', $recipe->id]])->first() != null) {
                return response()->json(['status' => 'fail'], 404);
            }else {
                Opinion::create(['creator_id' => Auth::id(), 'opinion' => $request->stars, 'recipe_id' => $recipe->id]);
                return response()->json(['status' => 'success'], 200);
            }
        }catch(Exception $error){
            return response()->json(['status' => 'fail'], 404);
        }
    }
}
