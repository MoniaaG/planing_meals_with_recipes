<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\StoreCategoryRequest;
use App\Http\Requests\StoreCategoryRequest as RequestsStoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index() {
        $recipe_categories = Category::all();
        return view('recipe_category.index',compact('recipe_categories'));
    }

    public function create() {
        return view('recipe_category.create');
    }

    public function store(RequestsStoreCategoryRequest $request) {
        $category = new Category();
        $category->name = $request->name;
        $category->save();
        return redirect()->route('recipe_category.index');
    }

    public function edit(Category $category) {
        return $category;
    }

    public function update(Category $category, UpdateCategoryRequest $request) {
        $category = Category::findOrFail($category->id);
        $category->name = $request->name;
        $category->save();
        return redirect()->route('recipe_category.index');
    }

    public function destroy(Category $category, Request $request) {
        //before deleting category add recipes from that to another !!!!
        try {
            $category->delete();
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $error) {
            return response()->json(['status' => 'fail'], 404);
        }
    }
}
