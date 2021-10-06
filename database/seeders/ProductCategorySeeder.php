<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product_categories = [
            1 => 'owoce',
            2 => 'warzywa',
            3 => 'inne',
        ];

        foreach ($product_categories as $key => $product_category) {
            DB::table('product_categories')->insert([
                'id' => $key,
                'name' => $product_category,
            ]);
        }
    }
}

