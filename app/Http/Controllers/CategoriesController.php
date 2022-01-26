<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\StoreCategoryRequest;
use App\Http\Requests\StoreCategoryRequest as RequestsStoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    public function __construct() {
        parent::__construct();
        $this->middleware('permission:recipe_category.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:recipe_category.edit', ['only' => ['edit']]);
        $this->middleware('permission:recipe_category.update', ['only' => ['update']]);
        $this->middleware('permission:recipe_category.delete', ['only' => ['destroy']]);
    }
    public function index() {
        $recipe_categories = Category::all()->paginate(4);
        return view('dashboard.recipe_category.recipe_category',compact('recipe_categories'));
    }

    public function create() {
        return view('dashboard.recipe_category.create');
    }

    public function store(RequestsStoreCategoryRequest $request) {
        $category = new Category();
        $category->name = $request->name;
        $category->save();
        return redirect()->route('recipe_category.index');
    }

    public function edit(Category $recipe_category) {
        $edit = true;
        return view('dashboard.recipe_category.create', compact('edit', 'recipe_category'));
    }

    public function update(Category $recipe_category, UpdateCategoryRequest $request) {
        $category = Category::findOrFail($recipe_category->id);
        $category->name = $request->name;
        $category->save();
        return redirect()->route('recipe_category.index');
    }

    public function destroy(Category $recipe_category, Request $request) {
        try {
            $recipe_category->delete();
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $error) {
            return response()->json(['status' => 'fail'], 404);
        }
    }
}
