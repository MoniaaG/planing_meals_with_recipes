<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Recipe;
use App\Models\Unit;
use App\Statements\ConstUnits;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class RecipeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recipe::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $unit = Unit::factory()->create(['unit' => ConstUnits::constUnits()[0]]);
        $product_category = ProductCategory::factory()->create(['name' => 'Kategoria produktu1']);
        $product = Product::factory()->create([
            'unit_id' => $unit->id,
            'product_category_id' => $product_category->id
        ]);
        $recipe_category = Category::factory()->create(['name' => 'Kategoria przepisu1']);
        return [
            'user_id' => Auth::id(),
            'share' => 1,
            'description' => 'description',
            'short_description' => 'short_description',
            'name' => 'recipe name',
            'category_id' => $recipe_category->id,
            'small_image' => UploadedFile::fake()->create('file.png'),
            'big_image' => UploadedFile::fake()->create('file.png'),
        ];
    }
}
