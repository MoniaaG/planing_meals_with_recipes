<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Artisan::call('permissions:assign');
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(RecipeSeeder::class);
    }
}
