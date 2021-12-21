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

    public function testAcceptProductProposition() {
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('moderator');
        $unit = Unit::factory()->create(['unit' => ConstUnits::constUnits()[0]]);
        $product_category = ProductCategory::factory()->create(['name' => 'Kategoria produktu']);
        $product_proposition = Product::factory()->create([
            'unit_id' => $unit->id,
            'product_category_id' => $product_category->id,
            'added' => 1,
        ]);
        $user->notify( new \App\Notifications\AddProductProposition($product_proposition, $user->id,$product_proposition->id));
        $response = $this->actingAs($user)->json('POST', route('dashboard.product_proposition.accept', ['product' => $product_proposition]));
        $response->assertStatus(200);
    }

    public function testRejectProductProposition() {
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('user');
        $unit = Unit::factory()->create(['unit' => ConstUnits::constUnits()[0]]);
        $product_category = ProductCategory::factory()->create(['name' => 'Kategoria produktu']);
        

        $product_proposition = Product::factory()->create([
            'unit_id' => $unit->id,
            'product_category_id' => $product_category->id,
            'added' => 1,
        ]);
        $user->notify( new \App\Notifications\AddProductProposition($product_proposition, $user->id,$product_proposition->id));
        $user->assignRole('moderator');
        $response = $this->actingAs($user)->json('DELETE', route('dashboard.product_proposition.reject', ['product' => $product_proposition]));
        $response->assertStatus(200);
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

    public function testUpdateProductQuantityInPantry() {
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('user');
        $pantry = Pantry::factory()->create(['owner_id' => $user->id]);
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

        $pantry_product = $pantry->products()->where([['product_id', $product_proposition->id],['pantry_id', $pantry->id] ])->first();
        $response = $this->actingAs($user)->json('POST', route('pantry.product.update', ['pantry_product' => $pantry_product->pivot->id, 'quantity' => 600]));
        $response->assertStatus(200);
    }

    public function testDeleteProductFromPantry() {
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('user');
        $pantry = Pantry::factory()->create(['owner_id' => $user->id]);
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

        $pantry_product = $pantry->products()->where([['product_id', $product_proposition->id],['pantry_id', $pantry->id] ])->first();
        $response = $this->actingAs($user)->json('DELETE', route('pantry.destroy_pantry_product', ['pantry_product' => $pantry_product->pivot->id]));
        $response->assertStatus(200);
    }
}
