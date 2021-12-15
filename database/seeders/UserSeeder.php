<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use \App\Models\Pantry;
use \App\Models\Calendar;
use \App\Models\Shoppinglist;
use \App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    /*
        Seeder tworzy po jednym użytkowniku z każdej roli [user, moderator, admin] 
        wraz z przypisanym do niego kalendarzem, spiżarnią i listą zakupów
    */
    public function run()
    {
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('user');
        Pantry::factory()->create(['owner_id' => $user->id]);
        Calendar::factory()->create(['owner_id' => $user->id]);
        Shoppinglist::factory()->create(['owner_id' => $user->id]);
        $moderator = User::factory()->create(['name' => 'moderator', 'email' => 'moderator@moderator.pl', 'password' => Hash::make('12345678')]); 
        $moderator->assignRole('moderator');
        Pantry::factory()->create(['owner_id' => $moderator->id]);
        Calendar::factory()->create(['owner_id' => $moderator->id]);
        Shoppinglist::factory()->create(['owner_id' => $moderator->id]);
        $admin = User::factory()->create(['name' => 'admin', 'email' => 'admin@admin.pl', 'password' => Hash::make('12345678')]); 
        $admin->assignRole('admin');
        Pantry::factory()->create(['owner_id' => $admin->id]);
        Calendar::factory()->create(['owner_id' => $admin->id]);
        Shoppinglist::factory()->create(['owner_id' => $admin->id]);
    }
}
