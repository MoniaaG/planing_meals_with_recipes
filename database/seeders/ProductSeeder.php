<?php

namespace Database\Seeders;
use \App\Models\Unit;
use Illuminate\Database\Seeder;

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
        \App\Models\Product::factory()->create(['name' => 'produkt_test1', 'unit_id' => 1, 'product_category_id' => 1]);
        \App\Models\Product::factory()->create(['name' => 'produkt_test2', 'unit_id' => 2, 'product_category_id' => 1]);
        \App\Models\Product::factory()->create(['name' => 'produkt_test3', 'unit_id' => 3, 'product_category_id' => 2]);
        \App\Models\Product::factory()->create(['name' => 'produkt_test4', 'unit_id' => 1, 'product_category_id' => 2]);
    }
}
