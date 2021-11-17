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
use App\Repositories\Interfaces\ProductRepositoryInterface;
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
    public $product_repository;

    public function __construct(Guard $auth, ProductRepositoryInterface $product_repository)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->pantry = Pantry::where('owner_id', Auth::user()->id)->first();
            $this->calendar = Calendar::where('owner_id', Auth::user()->id)->first();
            return $next($request);
        }); 
        $this->product_repository = $product_repository;  
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
        $this->product_repository->saveProductsToRecipeOrPantry($request, null,$this->pantry);
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

    public function productsNeededBetweenDate($start, $end, Calendar $calendar, $decision) 
    {
        if($decision == 1)
        $recipes_between_dates = $calendar->recipes()->whereBetween('start_at', [$start, $end])->get();
        else
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
        $today = Carbon::now()->toDateString();
        $start = $request->start;
        if($request->end == null) 
            $end = $start;
        else 
            $end = $request->end;
        $productToBuy = [];
        $list_type = $request->list_type;
        if($list_type == 1){
            $productToBuy = collect($this->productsNeededBetweenDate($start, $end, $this->calendar, $list_type));
        }
        else if($list_type == 2){
            $arrayOfProductsWithQuantity = $this->productsNeededBetweenDate($start, $end, $this->calendar, $list_type);
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
                $arrayOfProductsWithQuantityBeforeStart = $this->productsNeededBetweenDate($today, Carbon::parse($start)->subDays(1), $this->calendar, $list_type);
            }
            $arrayOfProductsWithQuantity = $this->productsNeededBetweenDate($start, $end, $this->calendar, $list_type);
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
        
        $shoppinglist = Shoppinglist::where('owner_id' ,'=', Auth::id())->first();
        $shoppinglist->start_date = $start;
        $shoppinglist->end_date = $end;
        $shoppinglist->save();
        if(count($productToBuy) > 0) {
            foreach($productToBuy as $productBuy){
                $shoppinglist->products()->sync([$productBuy['product_id'] => ['quantity' => $productBuy['quantity'], 'shoppinglist_id' => $shoppinglist->id]]);
            }
            
        }
        toastr()->success('Data has been saved successfully!');
        return view('shopping_list.show_shopping_list', compact('productToBuy', 'start', 'end', 'today'));
    }

    public function update_quantity(int $pantry_product, Request $request)
    {
        try {
            $this->pantry->products()->wherePivot('id', $pantry_product)->update(['quantity' => $request->quantity]);
        } catch (Exception $error) {
        }

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
