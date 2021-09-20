<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecipeController;
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
    return view('homepage');
});

Route::get('/test', function () {
    //return OpenFoodFacts::find('pomidor');//['quantity'];//['categories'];
    return OpenFoodFacts::barcode('5906827012705');
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
});

Route::get('/testt', function() {
    return view('recipe.xdd');
});
