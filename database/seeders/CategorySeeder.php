<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $categories = [
            1 => 'Obiad',
            2 => 'Kolacja',
            3 => 'Śniadanie',
            4 => 'Podwieczorek',
            5 => 'Owsianka',
        ];

        foreach ($categories as $key => $category) {
            DB::table('categories')->insert([
                // stałe id
                'id' => $key,
                // losowy wyraz
                'name' => $category,
            ]);
        }
    }
}
