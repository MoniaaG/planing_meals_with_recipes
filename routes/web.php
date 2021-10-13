<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CalendarController;
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

Route::get('/', function () {
    $recipes = Recipe::where('share', 1)->get();
    return view('homepage', compact('recipes'));
});

Route::get('/test', function () {
    //return OpenFoodFacts::find('pomidor');//['quantity'];//['categories'];
    return OpenFoodFacts::barcode('5900259115379');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function(){
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');


    Route::get('/recipe/show', [RecipeController::class, 'show'])->name('recipe.show');
    Route::get('/recipe/all', [RecipeController::class, 'index'])->name('recipe.index');
    Route::get('/recipe/create', [RecipeController::class, 'create'])->name('recipe.create');
    Route::post('/recipe/store', [RecipeController::class, 'store'])->name('recipe.store');
    //Route::get('/recipes/{option}', [RecipeController::class, 'index'])->name('recipe.index');

    Route::get('/calendar/show', [CalendarController::class, 'show'])->name('calendar.show');
    Route::get('/calendar/index', [CalendarController::class, 'index'])->name('calendar.index');
    Route::post('/calendar_day', [CalendarController::class, 'calendar_day'])->name('calendar_day');
    Route::post('/calendar_recipe/store', [CalendarController::class, 'calendar_recipe_store'])->name('calendar_recipe.store');
    Route::delete('/calendar/destory/{id}', [CalendarController::class, 'delete_recipe'])->name('calendar.delete_recipe');
});

Route::get('/testt', function() {
    return view('recipe.xdd');
});
