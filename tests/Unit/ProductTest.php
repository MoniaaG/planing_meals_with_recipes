<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \App\Models\Pantry;
use \App\Models\Calendar;
use \App\Models\Product;
use \App\Models\Shoppinglist;
use App\Statements\ConstUnits;
use App\Models\Unit;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class ProductTest extends TestCase
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
    public function testProductPropositionStore()
    { 
        // Testowanie zapisywania propozycji produktu
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('user');
        $unit = Unit::factory()->create(['unit' => ConstUnits::constUnits()[0]]);
        $product_category = ProductCategory::factory()->create(['name' => 'Kategoria produktu']);
        
        $response = $this->actingAs($user)->json('POST', route('product.proposition_store'), [
            'name' => "Nazwa proponowanego produktu", 
            'barcode' => null,
            'image' => UploadedFile::fake()->create('file.png'),
            'unit_id' => $unit->id,
            'product_category_id' => $product_category->id,
            'added' => 1,
        ]);
        $response->assertStatus(302);
    }

    public function testacceptProductProposition() {
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('moderator');
        $unit = Unit::factory()->create(['unit' => ConstUnits::constUnits()[0]]);
        $product_category = ProductCategory::factory()->create(['name' => 'Kategoria produktu']);
        $product_proposition = Product::factory()->create([
            'unit_id' => $unit->id,
            'product_category_id' => $product_category->id,
            'added' => 1,
        ]);

        $response = $this->actingAs($user)->json('POST', route('dashboard.product_proposition.accept', ['product' => $product_proposition]));
        $response->assertStatus(302);
    }

    public function testrejectProductProposition() {
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('user');
        $unit = Unit::factory()->create(['unit' => ConstUnits::constUnits()[0]]);
        $product_category = ProductCategory::factory()->create(['name' => 'Kategoria produktu']);
        

        $product_proposition = Product::factory()->create([
            'unit_id' => $unit->id,
            'product_category_id' => $product_category->id,
            'added' => 1,
        ]);
        $user->assignRole('moderator');
        $response = $this->actingAs($user)->json('DELETE', route('dashboard.product_proposition.reject', ['product' => $product_proposition]));
        $response->assertStatus(302);
    }

    public function testAddProductsToPantry() {
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('user');
        $unit = Unit::factory()->create(['unit' => ConstUnits::constUnits()[0]]);
        $product_category = ProductCategory::factory()->create(['name' => 'Kategoria produktu']);
        $product_proposition = Product::factory()->create([
            'unit_id' => $unit->id,
            'product_category_id' => $product_category->id,
            'added' => 0,
        ]);
        
        $products[0]['id'] = $product_proposition->id;
        $products[0]['barcode'] = "null";
        $products[0]['quantity'] = 250;
        $response = $this->actingAs($user)->json('POST', route('pantry.storeProduct', ['products' => $products]));
        $response->assertStatus(302);
    }
}
