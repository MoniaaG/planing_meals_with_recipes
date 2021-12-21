<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PantryController;
use App\Http\Controllers\ProductCategoryController;
use App\Models\Like;
use App\Models\Recipe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

//Route::get('/', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function(){
    /* Product */
    Route::get('/', [HomeController::class, 'home'])->name('homepage');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    
    Route::get('/product_proposition', [ProductController::class, 'proposition_create'])->name('product.proposition_create');
    Route::post('/product_proposition/store', [ProductController::class, 'proposition_store'])->name('product.proposition_store');
    /* Recipe */
    Route::get('/recipe/show/{recipe}', [RecipeController::class, 'show'])->name('recipe.show');
    Route::get('/recipe/all', [RecipeController::class, 'index'])->name('recipe.index');
    Route::get('/recipe/create', [RecipeController::class, 'create'])->name('recipe.create');
    Route::post('/recipe/store', [RecipeController::class, 'store'])->name('recipe.store');
    //Route::get('/recipes/{option}', [RecipeController::class, 'index'])->name('recipe.index');
    Route::post('/recipe/searchRecipe', [RecipeController::class, 'searchRecipes'])->name('searchRecipe');
    Route::get('/recipe/edit/{recipe}', [RecipeController::class, 'edit'])->name('recipe.edit');
    Route::put('/recipe/update/{recipe}', [RecipeController::class, 'update'])->name('recipe.update');
    Route::delete('/recipe/destroy/{recipe}', [RecipeController::class, 'destroy'])->name('recipe.destroy');
    Route::get('/recipe/favourities', [RecipeController::class, 'favourities'])->name('recipe.favourities');
    Route::get('recipes_shared', [RecipeController::class, 'recipes_shared'])->name('recipe.shared');
    /* Calendar */
    Route::get('/calendar/show', [CalendarController::class, 'show'])->name('calendar.show');
    Route::get('/calendar/index', [CalendarController::class, 'index'])->name('calendar.index');
    Route::post('/calendar_day', [CalendarController::class, 'calendar_day'])->name('calendar_day');
    Route::post('/calendar_recipe/store', [CalendarController::class, 'calendar_recipe_store'])->name('calendar_recipe.store');
    Route::delete('/calendar/destory/{id}', [CalendarController::class, 'delete_recipe'])->name('calendar.delete_recipe');
    Route::post('/calendar/assign/{id}', [CalendarController::class, 'assign_recipe'])->name('calendar.assign_recipe');
    Route::post('/calendar/unsign/{id}', [CalendarController::class, 'unsign_recipe'])->name('calendar.unsign_recipe');

    /*Pantry*/
    Route::get('/pantry/products', [PantryController::class, 'index'])->name('pantry.index');
    Route::get('/pantry/add_products', [PantryController::class, 'addProductToPantryBlade'])->name('pantry.addProduct_create');
    Route::post('/pantry/add_products', [PantryController::class, 'addProductToPantry'])->name('pantry.storeProduct');
    Route::post('/pantry/whatNeedToBuy', [PantryController::class, 'whatNeedToBuy'])->name('pantry.whatNeedToBuy');
    Route::post('/pantry/addProductToPantry',[PantryController::class, 'addProductToPantryFromList'])->name('pantry.addProductToPantryFromList');
    Route::delete('/pantry/destroy/{pantry_product}', [PantryController::class, 'destroy'])->name('pantry.destroy_pantry_product');
    Route::post('/product/update/{pantry_product}', [PantryController::class, 'update_quantity'])->name('pantry.product.update');

    /*Shopping list*/
    Route::get('/shopping_listt', [PantryController::class, 'searchShoppingList'])->name('pantry.searchShoppingList');
    Route::post('/shopping_list', [PantryController::class, 'whatNeedToBuy'])->name('pantry.whatNeedToBuy');

    Route::get('/shopping_list', [PantryController::class, 'showList'])->name('pantry.showList');

    /*Recipe Likes */
    Route::post('/like/{recipe}', [RecipeController::class, 'like'])->name('recipe.like');

    /* Opinion */
    Route::post('/opinion/{recipe}', [RecipeController::class, 'opinionAdd'])->name('opinion.add');

    /*Notifications*/
    Route::get('notifications', [HomeController::class, 'notifications'])->name('notifications');
    Route::get('/mark-as-read', [HomeController::class, 'markNotification'])->name('markNotification');
    Route::get('/mark-as-read/all', [HomeController::class,'markNotificationAll'])->name('markNotificationAll');

    Route::group(['middleware' => ['adminmoderator']], function(){
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard/product_proposition', [ProductController::class, 'product_proposition_index'])->name('dashboard.product_proposition.index');
        Route::post('/dashboard/product_proposition/accept/{product}', [ProductController::class, 'product_proposition_accept'])->name('dashboard.product_proposition.accept');
        Route::delete('/dashboard/product_proposition/reject/{product}', [ProductController::class, 'product_proposition_reject'])->name('dashboard.product_proposition.reject');

        Route::group(['middleware' => 'admin'], function(){
            Route::prefix('dashboard')->group(function() {

                /* Recipe */
                Route::get('/recipes/index', [RecipeController::class, 'indexDashboard'])->name('dashboard.recipe.index');
                /* Product category */
                Route::get('/product_category/index', [ProductCategoryController::class, 'index'])->name('product_category.index');
                Route::get('/product_category/create', [ProductCategoryController::class, 'create'])->name('product_category.create');
                Route::post('/product_category/store', [ProductCategoryController::class, 'store'])->name('product_category.store');
                Route::get('/product_category/edit/{product_category}', [ProductCategoryController::class, 'edit'])->name('product_category.edit');
                Route::put('/product_category/update/{product_category}', [ProductCategoryController::class, 'update'])->name('product_category.update');
                Route::delete('/product_category/destroy/{product_category}', [ProductCategoryController::class, 'destroy'])->name('product_category.destroy');

                /*Recipe category*/
                Route::get('/recipe_category/index', [CategoriesController::class, 'index'])->name('recipe_category.index');
                Route::get('/recipe_category/create', [CategoriesController::class, 'create'])->name('recipe_category.create');
                Route::post('/recipe_category/store', [CategoriesController::class, 'store'])->name('recipe_category.store');
                Route::get('/recipe_category/edit/{recipe_category}', [CategoriesController::class, 'edit'])->name('recipe_category.edit');
                Route::put('/recipe_category/update/{recipe_category}', [CategoriesController::class, 'update'])->name('recipe_category.update');
                Route::delete('/recipe_category/destroy/{recipe_category}', [CategoriesController::class, 'destroy'])->name('recipe_category.destroy');
            });
        });
    });
});
Route::get('/recipe/show/{recipe}', [RecipeController::class, 'show'])->name('recipe.show');
Route::get('/', [HomeController::class, 'home'])->name('homepage');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/', function () {
    $recipes_newest = Recipe::where('share', 1)->orderBy('created_at', 'desc')->limit(3)->get();
    $most_liked = Like::select('recipe_id', DB::raw('count(recipe_id) as counts'))->groupBy('recipe_id')->orderBy('counts', 'DESC')->limit(6)->pluck('recipe_id');
    $recipes_most_liked = [];
    foreach($most_liked as $liked){
        $recipes_most_liked[] = (Recipe::where('id', $liked)->first());
    }
    $recipes_most_liked = collect($recipes_most_liked);  
    return view('homepage', compact('recipes_newest', 'recipes_most_liked'));
})->name('homepage');

Route::get('/search',[RecipeController::class, 'search'])->name('recipe.search');
