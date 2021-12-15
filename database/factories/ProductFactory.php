<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'barcode' => null,
            'image' => 'image',//UploadedFile::fake()->image('file.png', 600, 600),
            'name' => 'product_name',
            'unit_id' => 1,
            'product_category_id' => 1,
            'added' => 1,
        ];
    }
}
