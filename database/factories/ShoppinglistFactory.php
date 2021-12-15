<?php

namespace Database\Factories;

use App\Models\Shoppinglist;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShoppinglistFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shoppinglist::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $now = Carbon::now()->toDateString();
        return [
            'owner_id' => 1,
            'start_date' => $now,
            'end_date' => $now
        ];
    }
}
