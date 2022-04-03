<?php

namespace Database\Seeders;

use App\Models\Product;
use \App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    //Seeder dodaje testowe produkty do bazy danych, nie pochodzące z API
    public function run()
    {
        $user = User::where('email', 'user@user.pl')->first();
        $product = Product::factory()->create(['name' => 'produkt_test1', 'unit_id' => 1, 'product_category_id' => 1]);
        $user->notify( new \App\Notifications\AddProductProposition($product, $user->id,$product->id));
        $product1 = Product::factory()->create(['name' => 'produkt_test2', 'unit_id' => 2, 'product_category_id' => 1]);
        $user->notify( new \App\Notifications\AddProductProposition($product1, $user->id,$product1->id));
        $product2 = Product::factory()->create(['name' => 'produkt_test3', 'unit_id' => 3, 'product_category_id' => 2]);
        $user->notify( new \App\Notifications\AddProductProposition($product2, $user->id,$product2->id));
        $product3 = Product::factory()->create(['name' => 'produkt_test4', 'unit_id' => 1, 'product_category_id' => 2]);
        $user->notify( new \App\Notifications\AddProductProposition($product3, $user->id,$product3->id));
    }
}
