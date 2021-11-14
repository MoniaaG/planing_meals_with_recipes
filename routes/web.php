<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PantryController;
use App\Http\Controllers\ProductCategoriesController;
use App\Models\ProductCategory;
use App\Models\Recipe;
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


Route::get('/test', function () {
    //return OpenFoodFacts::find('pomidor');//['quantity'];//['categories'];
    return OpenFoodFacts::barcode('5900259115379');
});

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/', [HomeController::class, 'home'])->name('homepage');
Route::group(['middleware' => ['auth']], function(){
    /* Product */
    Route::get('/', [HomeController::class, 'index'])->name('home');
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

    Route::get('/calendar/show', [CalendarController::class, 'show'])->name('calendar.show');
    Route::get('/calendar/index', [CalendarController::class, 'index'])->name('calendar.index');
    Route::post('/calendar_day', [CalendarController::class, 'calendar_day'])->name('calendar_day');
    Route::post('/calendar_recipe/store', [CalendarController::class, 'calendar_recipe_store'])->name('calendar_recipe.store');
    Route::delete('/calendar/destory/{id}', [CalendarController::class, 'delete_recipe'])->name('calendar.delete_recipe');

    /* Product category */
    Route::get('/product_category/index', [ProductCategoriesController::class, 'index'])->name('product_category.index');
    Route::get('/product_category/create', [ProductCategoriesController::class, 'create'])->name('product_category.create');
    Route::post('/product_category/store', [ProductCategoriesController::class, 'store'])->name('product_category.store');
    Route::get('/product_category/edit', [ProductCategoriesController::class, 'edit'])->name('product_category.edit');
    Route::put('/product_category/update', [ProductCategoriesController::class, 'update'])->name('product_category.update');
    Route::delete('/product_category/destroy/{product_category}', [ProductCategoriesController::class, 'destroy'])->name('product_category.destroy');

    /*Recipe category*/
    Route::get('/recipe_category/index', [CategoriesController::class, 'index'])->name('recipe_category.index');
    Route::get('/recipe_category/create', [CategoriesController::class, 'create'])->name('recipe_category.create');
    Route::post('/recipe_category/store', [CategoriesController::class, 'store'])->name('recipe_category.store');
    Route::get('/recipe_category/edit/{recipe_category}', [CategoriesController::class, 'edit'])->name('recipe_category.edit');
    Route::put('/recipe_category/update/{recipe_category}', [CategoriesController::class, 'update'])->name('recipe_category.update');
    Route::delete('/recipe_category/destroy/{recipe_category}', [CategoriesController::class, 'destroy'])->name('recipe_category.destroy');

    /*Pantry*/
    Route::get('/pantry/products', [PantryController::class, 'index'])->name('pantry.index');
    Route::get('/pantry/add_products', [PantryController::class, 'addProductToPantryBlade'])->name('pantry.addProduct_create');
    Route::post('/pantry/add_products', [PantryController::class, 'addProductToPantry'])->name('pantry.storeProduct');
    Route::post('/pantry/whatNeedToBuy', [PantryController::class, 'whatNeedToBuy'])->name('pantry.whatNeedToBuy');
    Route::post('/pantry/addProductToPantry',[PantryController::class, 'addProductToPantryFromList'])->name('pantry.addProductToPantryFromList');
    Route::delete('/pantry/destroy/{pantry_product}', [PantryController::class, 'destroy'])->name('pantry.destroy_pantry_product');
    Route::post('/product/update/{pantry_product}', [PantryController::class, 'update_quantity'])->name('pantry.product.update');

    /*Shopping list*/
    Route::get('/shopping_list', [PantryController::class, 'searchShoppingList'])->name('pantry.searchShoppingList');
    Route::post('/shopping_list', [PantryController::class, 'whatNeedToBuy'])->name('pantry.whatNeedToBuy');

    /*Recipe Likes */
    Route::post('/like/{recipe}', [RecipeController::class, 'like'])->name('recipe.like');

    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
});

Route::get('/testt', function() {
    return view('recipe.xdd');
});
