<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Recipe;
use App\Models\User;
use App\Statements\ProductsFromAPI;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'user@user.pl')->first();
        $product = Product::factory()->create(['name' => 'banany', 'unit_id' => 3, 'product_category_id' => 1, 'added' => 0]);
        $product1 = Product::factory()->create(['name' => 'olej', 'unit_id' => 2, 'product_category_id' => 3, 'added' => 0]);
        $product2 = Product::factory()->create(['name' => 'woda', 'unit_id' => 2, 'product_category_id' => 3, 'added' => 0]);
        $product3 = Product::factory()->create(['name' => 'produkt_test8', 'unit_id' => 1, 'product_category_id' => 2, 'added' => 0]);
        // 5906827014518 płatki owsiane 


        /* Recipe nr1*/
        $recipe1 = Recipe::factory()->create([
            'user_id' => $user->id, 
            'share' => 1,
            'description' => 'Płatki owsiane zblenować na mąke. Dodać 1 jajko, 1/4 szklanki wody. Banana rozgnieść i dodać do masy. Na rozgrzaną patelnie nalać 1 łyżkę oleju i rozłożyć masę na małe naleśniki. Piec aż spód naleśnika będzie lekko zaruminiony około 30 sekund. Można podać z malinami albo konfiturą.', 
            'short_description' => 'Płatki owsiane zblendować na mąke. Dodać 1 jajko, 1/4 szklanki wody. Banana rozgnieść i dodać do masy.',
            'name' => 'Małe naleśniki (pancakes)',
            'category_id' => 3,
            'small_image' => 'images\pancakes-gcb8098f32_640.jpg',
            'big_image' => 'images\pancakes-gf74a69bc9_1920.jpg',
        ]);

        Like::create(['recipe_id' => $recipe1->id, 'user_id' => 3]);
        $product_recipe1 = ProductsFromAPI::getAPIProductByBarcode('5906827014518'); //płatki owsiane
        $productCategoryAPI = strtolower(strtok(ProductsFromAPI::getAPIProductCategory($product_recipe1['_id']), ','));
        if(ProductCategory::where('name', $productCategoryAPI)->count() > 0){
            $productCategory = ProductCategory::where('name', $productCategoryAPI)->first();
        }else {
            $productCategory = ProductCategory::factory()->create(['name' => $productCategoryAPI]);
        }
        $product_recipe1_to_DB = Product::factory()->create(['name' => $product_recipe1['product_name'],'barcode' => 5906827014518, 'unit_id' => 1, 'product_category_id' => $productCategory->id, 'added' => 0]);


        $product_recipe2 = ProductsFromAPI::getAPIProductByBarcode('5051007084550'); //5051007084550 jajka L
        $productCategoryAPI = strtolower(strtok(ProductsFromAPI::getAPIProductCategory($product_recipe2['_id']), ','));
        if(ProductCategory::where('name', $productCategoryAPI)->count() > 0){
            $productCategory = ProductCategory::where('name', $productCategoryAPI)->first();
        }else {
            $productCategory = ProductCategory::factory()->create(['name' => $productCategoryAPI]);
        }
        $product_recipe2_to_DB = Product::factory()->create(['name' => $product_recipe2['product_name'],'barcode' => 5051007084550, 'unit_id' => 3, 'product_category_id' => $productCategory->id, 'added' => 0]);
        
        $recipe1->products()->attach($product_recipe1_to_DB->id, ['quantity' => 50]);
        $recipe1->products()->attach($product, ['quantity' => 1]);
        $recipe1->products()->attach($product1, ['quantity' => 20]);
        $recipe1->products()->attach($product2, ['quantity' => 60]);
        $recipe1->products()->attach($product_recipe2_to_DB, ['quantity' => 1]);

        /* Recipe nr1*/


        /* Recipe nr2*/


        $recipe2 = Recipe::factory()->create(['user_id' => $user->id, 
            'share' => 1,
            'description' => 'Makaron ugotować i odsączyć. Do garnka wrzucić brokuły, sól, czosnek i śmietane. Zagotować i dodać ugotowany wcześniej makaron.', 
            'short_description' => 'Makaron ugotować i odsączyć. Do garnka wrzucić brokuły, sól, czosnek i śmietane.',
            'name' => 'Makaron brokułowy',
            'category_id' => 1,
            'small_image' => 'images\easter-g1213f02f0_640.jpg',
            'big_image' => 'images\easter-g08f32cd55_1920.jpg',
        ]);

        $product4 = Product::factory()->create(['name' => 'brokuły mrożone', 'unit_id' => 1, 'product_category_id' => 2, 'added' => 0]);
        $product5 = Product::factory()->create(['name' => 'sól', 'unit_id' => 1, 'product_category_id' => 3, 'added' => 0]);
        $product6 = Product::factory()->create(['name' => 'śmietana 18%', 'unit_id' => 1, 'product_category_id' => 3, 'added' => 0]);
        $product7 = Product::factory()->create(['name' => 'Przyprawa czosnek', 'unit_id' => 1, 'product_category_id' => 3, 'added' => 0]);
        $product8 = Product::factory()->create(['name' => 'Makaron paski', 'unit_id' => 1, 'product_category_id' => 3, 'added' => 0]);
        $recipe2->products()->attach($product4->id, ['quantity' => 300]);
        $recipe2->products()->attach($product5->id, ['quantity' => 5]);
        $recipe2->products()->attach($product6->id, ['quantity' => 200]);
        $recipe2->products()->attach($product7->id, ['quantity' => 10]);
        $recipe2->products()->attach($product8->id, ['quantity' => 200]);

        Like::create(['recipe_id' => $recipe2->id, 'user_id' => 2]);
        Like::create(['recipe_id' => $recipe2->id, 'user_id' => 3]);
        /* Recipe nr2*/


        /* Recipe nr3*/

        $recipe3 = Recipe::factory()->create(['user_id' => $user->id, 
        'share' => 1,
        'description' => 'Umyć brzoskiwnie. Obrać banana wrzucić do blendera i dolać wody. Zblendować.', 
        'short_description' => 'Umyć brzoskiwnie. Obrać banana wrzucić do blendera i dolać wody. Zblendować.',
        'name' => 'Smoothie brzoskiwiniowo-bananowe',
        'category_id' => 3,
        'small_image' => 'images\smothie-ga5ae95362_640.jpg',
        'big_image' => 'images\smothie-gbaa8ba62b_1920.jpg',
    ]);

        $product9 = Product::factory()->create(['name' => 'brzoskwinia', 'unit_id' => 3, 'product_category_id' => 1, 'added' => 0]);
        $recipe3->products()->attach($product->id, ['quantity' => 1]);
        $recipe3->products()->attach($product2->id, ['quantity' => 60]);
        $recipe3->products()->attach($product9->id, ['quantity' => 1]);

        /* Recipe nr3*/
    }
}
