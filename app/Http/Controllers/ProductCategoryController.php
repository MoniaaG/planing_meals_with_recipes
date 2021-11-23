<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Repositories\Interfaces\ProductCategoryRepositoryInterface;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public $product_category_repository;

    public function __construct(ProductCategoryRepositoryInterface $product_category_repository)
    {
        $this->product_category_repository = $product_category_repository;
        $this->middleware('permission:product_category.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product_category.edit', ['only' => ['edit']]);
        $this->middleware('permission:product_category.update', ['only' => ['update']]);
        $this->middleware('permission:product_category.delete', ['only' => ['delete']]);
    }

    public function index() {
        $product_categories = ProductCategory::all();
        return view('dashboard.product_category.product_category',compact('product_categories'));
    }

    public function create() {
        return view('product_category.create');
    }

    public function store(Request $request) {
        $this->product_category_repository->store($request);
        return redirect()->route('product_category.index');
    }

    public function edit(ProductCategory $product_category) {
        $edit = true;
        return view('product_category.create', compact('product_category', 'edit'));
    }

    public function update(ProductCategory $product_category, Request $request) {
        $this->product_category_repository->update($request, $product_category);
        return redirect()->route('product_category.index');
    }

    public function destroy(ProductCategory $product_category, Request $request) {
        //before deleting category add recipes from that to another !!!!
        try {
            dd($product_category->delete());
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $error) {
            return response()->json(['status' => 'fail'], 404);
        }
    }
}
