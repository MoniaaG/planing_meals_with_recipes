<?php

namespace Tests\Feature;

use App\Models\Calendar;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \App\Models\Recipe;
use \App\Models\Category;
use App\Models\Pantry;
use \App\Models\Product;
use App\Statements\ConstUnits;
use App\Models\Unit;
use App\Models\ProductCategory;
use App\Models\Shoppinglist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();
        $this->artisan('permissions:assign');
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRecipeStoreAndLikeOpinionAdd()
    {
        // Testowanie zapisywania propozycji produktu
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('user');
        $unit = Unit::factory()->create(['unit' => ConstUnits::constUnits()[0]]);
        $product_category = ProductCategory::factory()->create(['name' => 'Kategoria produktu']);
        
        $product_proposition1 = Product::factory()->create([
            'unit_id' => $unit->id,
            'product_category_id' => $product_category->id,
            'added' => 0,
        ]);

        $product_proposition2 = Product::factory()->create([
            'unit_id' => $unit->id,
            'product_category_id' => $product_category->id,
            'added' => 0,
        ]);

        $recipe_category = Category::factory()->create(['name' => 'Kategoria przepisu']);
        // Produkty z bazy danych
        $products[0]['id'] = $product_proposition1->id;
        $products[0]['barcode'] = null;
        $products[0]['quantity'] = 150;
        $products[1]['id'] = $product_proposition2->id;
        $products[1]['barcode'] = null;
        $products[1]['quantity'] = 600;
        $response = $this->actingAs($user)->json('POST', route('recipe.store'), [
            'name' => "Nazwa przepisu 1", 
            'description' => 'description',
            'short_description' => 'short_secription',
            'small_image' => UploadedFile::fake()->create('file.png'),
            'big_image' => UploadedFile::fake()->create('file.png'),
            'share' => 1,
            'category_id' => $recipe_category->id,
            'products' => $products
        ])->assertRedirect(route('recipe.index')); //Po pomy??lnym dodaniu przepisu do bazy nast??puije przekierowanie na adres wszystkich przepis??w danego u??ytkownika
        $response->assertStatus(302); // Operacja dodawania przepisu przebieg??a pomy??lnie

        $recipe = Recipe::where('share', 1)->first();

        //dodanie polubienia do przepisu przez u??ytkownika, kt??ry ten przepis stworzy?? oraz innego
        $response = $this->actingAs($user)->json('POST', route('recipe.like' , ['recipe' => $recipe]));
        $response->assertStatus(403); // brak uprawnie?? do dodawania polubie?? do w??asnych przepis??w
        $user2 = User::factory()->create(['name' => 'user2', 'email' => 'user2@user.pl', 'password' => Hash::make('12345678')]); 
        $user2->assignRole('user');
        $response = $this->actingAs($user2)->json('POST', route('recipe.like' , ['recipe' => $recipe]));
        $response->assertStatus(200);

        //Dodanie opinii do przepisu 
        $response = $this->actingAs($user)->json('POST', route('opinion.add' , ['recipe' => $recipe]), [
            'stars' => 4, //Oznaczenie opinii w requescie jako 'stars'
        ]);
        $response->assertStatus(403); //Brak uprawnie?? do dodawania polubie?? do w??asnych przepis??w
        $response = $this->actingAs($user2)->json('POST', route('opinion.add' , ['recipe' => $recipe]), [
            'stars' => 4,
        ]);
        $response->assertStatus(200); //Opinia zosta??a pomy??lnie dodana przez innego u??ytkonika

        //Sprawdzenie czy zostanie zablokownae dodanie opinii je??li ju?? raz j?? dodano
        $response = $this->actingAs($user2)->json('POST', route('opinion.add' , ['recipe' => $recipe]), [
            'stars' => 3,
        ]);
        $response->assertStatus(404); // Dodanie 2 raz opinii do przepisu zabronione
    }

    public function testAddRecipeToCalendarAlsoGenarateAndShowShoppinglist() {
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('user');
        Pantry::factory()->create(['owner_id' => $user->id]);
        $calendar = Calendar::factory()->create(['owner_id' => $user->id]);
        Shoppinglist::factory()->create(['owner_id' => $user->id]);
        $unit = Unit::factory()->create(['unit' => ConstUnits::constUnits()[0]]);
        $product_category = ProductCategory::factory()->create(['name' => 'Kategoria produktu']);
        $product_proposition = Product::factory()->create([
            'unit_id' => $unit->id,
            'product_category_id' => $product_category->id,
        ]);

        $user->assignRole('moderator');
        $response = $this->actingAs($user)->json('POST', route('dashboard.product_proposition.accept', ['product' => $product_proposition]));
        $user->removeRole('moderator'); // Usuwanie roli moderaotra potrzebnej do zaakceptowania produkt??w i dodanie przepisu jako u??ytkownik
        $category = Category::factory()->create(['name' => 'new recipe category']);
        $recipe = Recipe::factory()->create(['category_id' => $category->id]);
        $recipe->products()->attach($product_proposition->id, ['quantity' => 150]);
        $response = $this->actingAs($user)->json('POST', route('calendar_recipe.store', [
            'recipe_id' => $recipe->id,
            'start_at' => Carbon::today()->toDateString(),
            'text_color' => '#ffffff',
            'background_color' => '#000000',
        ]));
        $response->assertStatus(302);
        $products[0]['id'] = $product_proposition->id;
        $products[0]['barcode'] = "null";
        $products[0]['quantity'] = 250;
        $response = $this->actingAs($user)->json('POST', route('pantry.storeProduct', ['products' => $products]));
        $response = $this->actingAs($user)->json('POST', route('pantry.whatNeedToBuy'), [
            'start' => Carbon::today()->toDateString(),
        ]);
        $response->assertStatus(302);
        $response = $this->actingAs($user)->json('GET', route('pantry.showList'));
        $response->assertStatus(200);

        //Oznaczenie przepisu w kalendarzu jako ugotowany
        $recipe_calendar =  $calendar->recipes()->where([['recipe_id', $recipe->id], ['start_at', Carbon::today()->toDateString()]])->first();
        $response = $this->actingAs($user)->json('POST', route('calendar.assign_recipe', ['id' => $recipe_calendar->pivot->id]));
        $response->assertStatus(200);

        //Odznaczenie przepisu w kalendarzu i ustawienie go jako nieugotowanego
        $recipe_calendar =  $calendar->recipes()->where([['recipe_id', $recipe->id], ['start_at', Carbon::today()->toDateString()]])->first();
        $response = $this->actingAs($user)->json('POST', route('calendar.unsign_recipe', ['id' => $recipe_calendar->pivot->id]));
        $response->assertStatus(200);
    }

    public function testDeleteRecipeFromCalendar() {
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('user');
        $pantry = Pantry::factory()->create(['owner_id' => $user->id]);
        $calendar = Calendar::factory()->create(['owner_id' => $user->id]);
        $category = Category::factory()->create(['name' => 'new recipe category']);
        $unit = Unit::factory()->create(['unit' => ConstUnits::constUnits()[0]]);
        $product_category = ProductCategory::factory()->create(['name' => 'Kategoria produktu']);
        $product_proposition = Product::factory()->create([
            'unit_id' => $unit->id,
            'product_category_id' => $product_category->id,
            'added' => 0,
        ]);
        $recipe = Recipe::factory()->create(['category_id' => $category->id, 'user_id' => $user->id]);
        $recipe->products()->attach($product_proposition->id, ['quantity' => 150]);
        $response = $this->actingAs($user)->json('POST', route('calendar_recipe.store', [
            'recipe_id' => $recipe->id,
            'start_at' => Carbon::today()->toDateString(),
            'text_color' => '#ffffff',
            'background_color' => '#000000',
        ]));
        $recipe_calendar = $calendar->recipes()->where([['recipe_id', $recipe->id], ['start_at', Carbon::today()->toDateString()]])->first();
        $response = $this->actingAs($user)->json('DELETE', route('calendar.delete_recipe', [ 'id' => 
        $recipe_calendar->pivot->id]));
        $response->assertStatus(200);
    }

    public function testUpdateRecipe() {
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('user');
        $pantry = Pantry::factory()->create(['owner_id' => $user->id]);
        $calendar = Calendar::factory()->create(['owner_id' => $user->id]);
        $category = Category::factory()->create(['name' => 'new recipe category']);
        $unit = Unit::factory()->create(['unit' => ConstUnits::constUnits()[0]]);
        $product_category = ProductCategory::factory()->create(['name' => 'Kategoria produktu']);
        $product_proposition = Product::factory()->create([
            'unit_id' => $unit->id,
            'product_category_id' => $product_category->id,
            'added' => 0,
        ]);
        $recipe = Recipe::factory()->create(['category_id' => $category->id, 'user_id' => $user->id, 'share' => false]);
        $recipe->products()->attach($product_proposition->id, ['quantity' => 150]);
        
        $products[0]['id'] = $product_proposition->id;
        $products[0]['barcode'] = null;
        $products[0]['quantity'] = 250;
        $response = $this->actingAs($user)->json('PUT', route('recipe.update', [ 'recipe' => 
        $recipe]), [
            'name' => 'edit product name',
            'short_description' => $recipe->short_description,
            'description' => 'edit description',
            'category_id' => $category->id, 
            'products' => $products
        ]);
        $response->assertStatus(302);
    }

    public function testDeleteRecipe() {
        $user = User::factory()->create(['name' => 'user', 'email' => 'user@user.pl', 'password' => Hash::make('12345678')]); 
        $user->assignRole('user');
        $pantry = Pantry::factory()->create(['owner_id' => $user->id]);
        $calendar = Calendar::factory()->create(['owner_id' => $user->id]);
        $category = Category::factory()->create(['name' => 'new recipe category']);
        $unit = Unit::factory()->create(['unit' => ConstUnits::constUnits()[0]]);
        $product_category = ProductCategory::factory()->create(['name' => 'Kategoria produktu']);
        $product_proposition = Product::factory()->create([
            'unit_id' => $unit->id,
            'product_category_id' => $product_category->id,
            'added' => 0,
        ]);
        $recipe = Recipe::factory()->create(['category_id' => $category->id, 'user_id' => $user->id]);
        $recipe->products()->attach($product_proposition->id, ['quantity' => 150]);
        $response = $this->actingAs($user)->json('DELETE', route('recipe.destroy', [ 'recipe' => 
        $recipe]));
        $response->assertStatus(200);
    }
}
