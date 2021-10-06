<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            1 => 'g',
            2 => 'ml',
            3 => 'szt',
        ];

        foreach ($units as $key => $unit) {
            DB::table('units')->insert([
                'id' => $key,
                'unit' => $unit,
            ]);
        }
    }
}
