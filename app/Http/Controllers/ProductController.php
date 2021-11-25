<?php

namespace App\Http\Controllers;

use App\Models\Pantry;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Models\User;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use OpenFoodFacts\Laravel\Facades\OpenFoodFacts;
class ProductController extends Controller
{
    public $product_repository;

    public function __construct(ProductRepositoryInterface $product_repository)
    {
        parent::__construct();
        $this->product_repository = $product_repository;
        $this->middleware('permission:product.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product.edit', ['only' => ['edit']]);
        $this->middleware('permission:product.update', ['only' => ['update']]);
        $this->middleware('permission:product.delete', ['only' => ['delete']]);
        $this->middleware('permission:product.offer', ['only' => ['proposition_create', 'proposition_store']]);
    }

    public function index()
    {
        // zapytanie do bazy plus zapytanie do API

        $products = Product::all();
    }

    public function searchProducts(Request $request)
    {
        return $this->product_repository->searchProducts($request);
    }

    public function create()
    {
        $units = Unit::all();
        return view('product.create', compact('units'));
    }

    public function store(Request $request)
    {
        
        return redirect()->route('product.index');
    }

    public function edit(Product $product)
    {
        return view('product.edit', compact($product));
    }

    public function update(Product $product, Request $request)
    {
        $this->product_repository->update($request, $product);
        return redirect()->route('product.index');
    }

    public function delete(Product $product)
    {
        $this->product_repository->destroy($product);
        return redirect()->route('product.index');
    }

    public function proposition_create()
    {
        $units = Unit::all();
        $product_categories = ProductCategory::all();
        return view('product.product_proposition', compact('units', 'product_categories'));
    }

    public function proposition_store(Request $request)
    {
        try{
            $product = $this->product_repository->propositionStore($request);
            Auth::user()->notify( new \App\Notifications\AddProductProposition($product, Auth::id(),$product->id));
            toastr()->success('Podano produkt do proponowanych!');
        }catch (Exception $error) {
            toastr()->error('Próba dodania propozycji produktu nie powiodła się. Spróbuj ponownie!');
            return back();
        }
        return redirect()->back();
    }

    public function product_proposition_index()
    {
        $product_propositions = Product::where('added',1)->get();
        return view('dashboard.product_proposition.product_proposition', compact('product_propositions'));
    }

    public function product_proposition_accept(Product $product)
    {
        try{
            $notification =json_decode(DB::table('notifications')->whereJsonContains('data->product_id', $product->id)->where('type','App\Notifications\AddProductProposition')->first()->data);
            $product->added = 0;
            $product->save();
            $user = User::where('id', $notification->user_id)->first();
            $user->notify(new \App\Notifications\ProductPropositionAccept($product, $notification->user_id, $product->id));
            return redirect()->route('dashboard.product_proposition.index');
        }catch (Exception $error) {
            return back();
        }
    }

    public function product_proposition_reject(Product $product)
    {
        try{
            $notification =json_decode(DB::table('notifications')->whereJsonContains('data->product_id', $product->id)->where('type','App\Notifications\AddProductProposition')->first()->data);
            $user = User::where('id', $notification->user_id)->first();
            $user->notify(new \App\Notifications\ProductPropositionReject($product, $notification->user_id, $product->id));
            $product->delete();
            return redirect()->route('dashboard.product_proposition.index');
        }catch (Exception $error) {
            return back();
        }
    }
}
