<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \App\Models\Pantry;
use \App\Models\Calendar;
use \App\Models\Shoppinglist;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();
        $this->artisan('permissions:assign');
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCategoryStore()
    { 
        // Testowanie zapisywania kategorii przepisu w zależności od uprawnień dla danej roli
        //user i userModerator nie mają uprawnień do zapisywania, userAdmin posiada
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('user');
        Pantry::factory()->create(['owner_id' => $user->id]);
        Calendar::factory()->create(['owner_id' => $user->id]);
        Shoppinglist::factory()->create(['owner_id' => $user->id]);
        $response = $this->actingAs($user)->json('POST', route('recipe_category.store'), [
            'name' => "Nowa kategoria przepisu", 
        ]);
        $response->assertStatus(403);

        $userModerator = $user;
        $userModerator->assignRole('moderator');
        $response = $this->actingAs($userModerator)->json('POST', route('recipe_category.store'), [
            'name' => "Nowa kategoria przepisu 2", 
        ]);
        $response->assertStatus(403);

        $userAdmin = $user;
        $userAdmin->assignRole('admin');
        $response = $this->actingAs($user)->json('POST', route('recipe_category.store'), [
            'name' => "Nowa kategoria przepisu 1", 
        ]);
        $response->assertStatus(302);
    }
}
