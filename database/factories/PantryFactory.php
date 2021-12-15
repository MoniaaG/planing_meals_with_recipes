<?php

namespace Database\Factories;

use App\Models\Pantry;
use Illuminate\Database\Eloquent\Factories\Factory;

class PantryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pantry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'owner_id' => 1,
        ];
    }
}
