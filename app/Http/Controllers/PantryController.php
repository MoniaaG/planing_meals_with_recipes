<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShoppingListRequest;
use App\Models\Calendar;
use App\Models\Pantry;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Shoppinglist;
use App\Models\Unit;
use App\Models\User;
use App\Statements\ConstProductCategory;
use App\Statements\ProductsFromAPI;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use OpenFoodFacts\Laravel\Facades\OpenFoodFacts;
use Psy\VarDumper\Dumper;

class PantryController extends Controller
{
    public $pantry;
    public $calendar;

    public function __construct(Guard $auth)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->pantry = Pantry::where('owner_id', Auth::user()->id)->first();
            $this->calendar = Calendar::where('owner_id', Auth::user()->id)->first();
            return $next($request);
        });   
    }

    public function index()
    {
        $pantry_products = $this->pantry->products()->get();
        $today = Carbon::now()->toDateString();
        return view('pantry.allProductsInPantry', compact('pantry_products', 'today'));
    }


    public function addProductToPantryBlade()
    {
        return view('pantry.addProductsToPantry');
    }
 
    public function addProductToPantry(Request $request)
    {

        foreach($request->products as $product) {
            $savedProduct = $product;
            
            if($product['barcode'] != "null")
            {
                $productFromAPI = Product::where('barcode', $product['barcode'])->count();
                if(!$productFromAPI)
                {
                    $productBarcode = strtolower(strtok(ProductsFromAPI::getAPIProductCategory($product['barcode']), ','));
                    $newProduct = new Product();
                    $newProduct->name = $product['name'];
                    $newProduct->barcode = $product['barcode'];
                    $newProduct->image = 'image';
                    $newProduct->unit_id = Unit::where('unit', $product['unit_name'])->first()->id;
                    if(ProductCategory::where('name', 'like', $productBarcode)->count()) {
                        $newProduct->product_category_id = ProductCategory::where('name', 'like', $productBarcode)->first()->id;
                    }else {
                        $productCategory = ProductCategory::create([
                            'name' => $productBarcode,
                        ]);
                        $newProduct->product_category_id = $productCategory->id;
                    }
                    $newProduct->save();
                    $savedProduct = $newProduct;
                }else {
                    $savedProduct = Product::where('barcode', $product['barcode'])->first();
                }
            }else {
                $savedProduct = Product::findOrFail($product['id']);
            }
            $this->pantry->products()->attach($savedProduct->id, ['quantity' => $product['quantity'], 'expiration_date' => $product['expiration_date']]);

        }
        return redirect()->back();
    }

    public function addProductToPantryFromList(Request $request)
    {
        foreach($request->products as $product){
            $savedProduct = Product::findOrFail($product['id']);
            $this->pantry->products()->attach($savedProduct->id, ['quantity' => $product['quantity'], 'expiration_date' => $product['expiration_date']]);
        }
    }

    public function searchShoppingList() 
    {
        $today = Carbon::now()->toDateString();
        return view('shopping_list.search_shopping_list', compact('today'));
    }

    public function productsNeededBetweenDate($start, $end, Calendar $calendar) 
    {
        $recipes_between_dates = $calendar->recipes()->where('cooked', false)->whereBetween('start_at', [$start, $end])->get();
        $array = [];
        foreach($recipes_between_dates as $recipe) {
            foreach($recipe->products()->get() as $product) {
                $array[] = $product;
            }
        }
        $arrayOfProductsWithQuantity = [];

        foreach(collect($array) as $key => $col)
        {
            $licznik = 0;
            if(collect($arrayOfProductsWithQuantity)->where('product_id', $col->id)->count() > 0){
                continue;
            }else {
                $products = collect($array)->where('id',$col->id);
                $quantity = 0;
                foreach($products as $product) {
                    $quantity += $product->pivot['quantity'];
                }
                
                if($quantity > 0){
                    $arrayOfProductsWithQuantity[$key]['product_id'] = $col->id;
                    $products = collect($array)->where('id',$col->id);
                    $arrayOfProductsWithQuantity[$key]['name'] = $products->first()->name;
                    $arrayOfProductsWithQuantity[$key]['quantity'] = $quantity;
                }   
                $licznik++;
            }
        }

        return $arrayOfProductsWithQuantity;
    }
    public function whatNeedToBuy(ShoppingListRequest $request)
    {
        //dd($request->list_type);
        $today = Carbon::now()->toDateString();
        $start = $request->start;
        if($request->end == null) 
            $end = $start;
        else 
            $end = $request->end;
        $productToBuy = [];
        $list_type = $request->list_type;
        if($list_type == 1){
            $productToBuy = collect($this->productsNeededBetweenDate($start, $end, $this->calendar));
        }
        else if($list_type == 2){
            $arrayOfProductsWithQuantity = $this->productsNeededBetweenDate($start, $end, $this->calendar);
            $productsFromUserPantry = $this->pantry->products()->get();
            foreach(collect($arrayOfProductsWithQuantity) as $key => $productNeed)
            {
                $quantityOfProductFromPantry = $productsFromUserPantry->where('id', $productNeed['product_id'])->pluck('pivot.quantity')->sum();
                if(isset($arrayOfProductsWithQuantityBeforeStart))
                $howManyNeed = 0;
                if(!($quantityOfProductFromPantry > $productNeed['quantity']))
                {
                    $howManyNeed = $productNeed['quantity'] - $quantityOfProductFromPantry;
                    if($howManyNeed > 0)
                    {
                        $productToBuy[$key]['quantity'] = $howManyNeed;
                        $productToBuy[$key]['product_id'] = $productNeed['product_id'];
                        $productToBuy[$key]['name'] = $productNeed['name'];
                    }
                }
            }
            $productToBuy = collect($productToBuy);
        }
        else if($list_type == 3){
            $arrayOfProductsWithQuantityBeforeStart = [];
            if($start > $today){
                $arrayOfProductsWithQuantityBeforeStart = $this->productsNeededBetweenDate($today, Carbon::parse($start)->subDays(1), $this->calendar);
            }
            $arrayOfProductsWithQuantity = $this->productsNeededBetweenDate($start, $end, $this->calendar);
            $productsFromUserPantry = $this->pantry->products()->get();
            foreach(collect($arrayOfProductsWithQuantity) as $key => $productNeed)
            {
                $quantityOfProductFromPantry = $productsFromUserPantry->where('id', $productNeed['product_id'])->pluck('pivot.quantity')->sum();
                if(isset($arrayOfProductsWithQuantityBeforeStart))
                $productToBuyToday = collect($arrayOfProductsWithQuantityBeforeStart)->where('product_id', $productNeed['product_id'])->first();
                $howManyNeed = 0;
                if(isset($productToBuyToday['quantity']))
                    $quantityOfProductFromPantry -= $productToBuyToday['quantity'];
                    if($quantityOfProductFromPantry < 0) $quantityOfProductFromPantry = 0; 
                if(!($quantityOfProductFromPantry > $productNeed['quantity']))
                {
                    $howManyNeed = $productNeed['quantity'] - $quantityOfProductFromPantry;
                    if($howManyNeed > 0)
                    {
                        $productToBuy[$key]['quantity'] = $howManyNeed;
                        $productToBuy[$key]['product_id'] = $productNeed['product_id'];
                        $productToBuy[$key]['name'] = $productNeed['name'];
                    }
                }
            }
            $productToBuy = collect($productToBuy);
        }
        else {
            //toastr błędne dane podaj właściwe
        }
        if(count($productToBuy) > 0) {
            if($start > $today)
                $start = $today;
            else $start = $request->start;
            $shoppinglist = Shoppinglist::where('owner_id' ,'=', Auth::id())->first();
            if(isset($shopinglist)) {
                foreach($productToBuy as $productBuy){
                    $shoppinglist->products()->sync([$productBuy['product_id'] => ['quantity' => $productBuy['quantity'], 'shoppinglist_id' => $shoppinglist->id]]);
                }
            }else {
                $shoppinglist = Shoppinglist::create([
                    'owner_id' => Auth::id(),
                    'start_date' => $start, 
                    'end_date' => $end,
                ]);
                foreach($productToBuy as $productBuy){
                    $shoppinglist->products()->attach([$productBuy['product_id'] => ['quantity' => $productBuy['quantity'], 'shoppinglist_id' => $shoppinglist->id]]);
                }
            }
        }
        return view('shopping_list.show_shopping_list', compact('productToBuy', 'start', 'end', 'today'));
    }

    public function destroy(int $pantry_product)
    {
        try {
            $this->pantry->products()->wherePivot('id', $pantry_product)->detach();
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $error) {
            return response()->json(['status' => 'fail'], 404);
        }
    }
}
