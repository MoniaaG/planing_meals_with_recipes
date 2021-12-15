<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \App\Models\Pantry;
use \App\Models\Calendar;
use \App\Models\Shoppinglist;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductCategoryTest extends TestCase
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
    public function testProductCategoryStore()
    { 
        // Testowanie zapisywania kategorii produktu w zależności od uprawnień
        //user i userModerator nie mają uprawnień do zapisywania, userAdmin posiada
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('user');
        Pantry::factory()->create(['owner_id' => $user->id]);
        Calendar::factory()->create(['owner_id' => $user->id]);
        Shoppinglist::factory()->create(['owner_id' => $user->id]);
        $response = $this->actingAs($user)->json('POST', route('product_category.store'), [
            'name' => "Nowa kategoria produktu", 
        ]);
        $response->assertStatus(403); //potwierdzenie, że operacja jest niedozwolona dla roli user

        $userModerator = $user;
        $userModerator->assignRole('moderator');
        $response = $this->actingAs($userModerator)->json('POST', route('product_category.store'), [
            'name' => "Nowa kategoria produktu 1", 
        ]);
        $response->assertStatus(403); //potwierdzenie, że operacja jest niedozwolona dla roli moderator

        $userAdmin = $user;
        $userAdmin->assignRole('admin');
        $response = $this->actingAs($userAdmin)->json('POST', route('product_category.store'), [
            'name' => "Nowa kategoria produktu 2", 
        ]);
        $response->assertStatus(302); //potwierdzenie, że operacja zapisu powiodła się dla roli admin
    }
}
