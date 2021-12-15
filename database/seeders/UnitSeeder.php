<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Statements\ConstUnits;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    //Seeder dodaje do bazy danych 3 używane w systemie jednostki produktów
    public function run()
    {
        $units = ConstUnits::constUnits();

        foreach ($units as $key => $unit) {
            DB::table('units')->insert([
                'id' => ($key+1),
                'unit' => $unit[$key],
            ]);
        }
    }
}
