<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Category $category) {
        return $category;
    }

    public function index() {
        return Category::all();
    }

    public function create(Request $request) {
        $category = new Category();
        $category->name = $request->name;
        $category->save();
    }

    public function edit(Category $category) {
        return $category;
    }

    public function update(Category $category, Request $request) {
        $category = Category::findOrFail($category->id);
        $category->name = $request->name;
        $category->save();
    }

    public function delete(Category $category, Request $request) {
        //before deleting category add recipes from that to another !!!!
        $category->delete();
    }
}
