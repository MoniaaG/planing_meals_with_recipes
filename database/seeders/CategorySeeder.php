<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    //Seeder dodaje do bazy danych 5 przykładowych kategorii przepisów
    public function run()
    {

        $categories = [
            'Obiad',
            'Kolacja',
            'Śniadanie',
            'Podwieczorek',
            'Owsianka',
        ];

        foreach ($categories as $key => $category) {
            \App\Models\Category::factory()->create([
                'name' => $category
            ]);
        }
    }
}
