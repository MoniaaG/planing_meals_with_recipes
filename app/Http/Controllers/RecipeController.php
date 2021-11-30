<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddRecipeRequest;
use App\Models\Calendar;
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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class RecipeController extends Controller
{
    public $recipe_repository;
    public $product_repository;

    public function __construct(RecipeRepositoryInterface $recipe_repository, ProductRepositoryInterface $product_repository)
    {
        parent::__construct();
        $this->recipe_repository = $recipe_repository;
        $this->product_repository = $product_repository;
        $this->middleware('permission:recipe.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:recipe.edit', ['only' => ['edit']]);
        $this->middleware('permission:recipe.update', ['only' => ['update']]);
        $this->middleware('permission:recipe.delete', ['only' => ['destroy']]);
        $this->middleware('permission:recipe.offer', ['only' => ['proposition_create', 'proposition_store']]);
    }

    public function show(Recipe $recipe) {
        if(!Auth::check() && $recipe->share)  {
            $opinion = Opinion::where([['creator_id', Auth::id()], ['recipe_id', $recipe->id]])->first();
            $recipe = $recipe->where('id', $recipe->id)->with('products.unit')->first();
            return view('recipe.show', compact('recipe', 'opinion'));     
        }else if(!Auth::check() && !$recipe->share) abort(403);
        if(!Gate::allows('recipe-show', $recipe))
            abort(403);
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
        if($request->products){
            $recipe = $this->recipe_repository->store($request);
            $this->product_repository->saveProductsToRecipeOrPantry($request, $recipe,null);
        }
        return redirect()->route('recipe.index');
    }

    public function edit(Recipe $recipe) {
        if(!Gate::allows('recipe-edit-destroy', $recipe))
            abort(403);
        $categories = Category::all();
        return view('recipe.edit', compact('recipe', 'categories'));
    }

    public function update(Recipe $recipe, Request $request) {
        if(!Gate::allows('recipe-edit-destroy', $recipe))
            abort(403);
        $recipe = Recipe::findOrFail($recipe->id);
        $recipe->user_id = Auth::id();
        $recipe->share ? $recipe->share = true : $recipe->share = $request->share;
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
        if(!$recipe->share)
        $this->product_repository->saveProductsToRecipeOrPantry($request, $recipe,null, 1);
        return redirect()->route('recipe.index');
    }

    public function destroy(Recipe $recipe) {
        if(!Gate::allows('recipe-show', $recipe))
            abort(403);
        try {
            $calendar = Calendar::where('owner_id', Auth::id())->first();
            $calendar->recipes()->detach($recipe->id);
            if($recipe->products()->get()->pluck('pivot.product_id')->count() > 0)
            $recipe->products()->detach($recipe->products()->get()->pluck('pivot.product_id'));
            $recipe->delete();
            return response()->json(['status' => 'success'], 200);
        }catch(Exception $error){
            return response()->json(['status' => 'fail'], 404);
        }
    }

    public function like(Recipe $recipe, Request $request) {
        if(!Gate::allows('recipe-like-opinion', $recipe))
            abort(403);
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

    public function opinionAdd(Request $request, Recipe $recipe) {
        if(!Gate::allows('recipe-like-opinion', $recipe))
            abort(403);
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
        return view('search', compact('newArray'));
    }

    public function favourities() {
        $liked_recipes = Like::where('user_id', Auth::id())->get()->pluck('recipe_id');
        $recipes = Recipe::whereIn('id', $liked_recipes)->get();
        return view('recipe.favourities', compact('recipes'));
    }
}
