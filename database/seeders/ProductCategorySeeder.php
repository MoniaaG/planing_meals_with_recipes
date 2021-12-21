<?php

namespace Database\Seeders;

use App\Statements\ConstProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    //Seeder dodaje do bazy danych 2 przykładowe kategorie produktów
    public function run()
    {
        $product_categories = [
            'owoce',
            'warzywa',
            ConstProductCategory::INNA,
        ];

        foreach ($product_categories as $product_category) {
            \App\Models\ProductCategory::factory()->create([
                'name' => $product_category
            ]);
        }
    }
}

