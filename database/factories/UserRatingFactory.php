<?php

namespace Database\Factories;

use App\Models\UserRating;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserRatingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserRating::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rating' => $this->faker->randomNumber(0),
            'rating_datetime' => $this->faker->dateTime(),
            'product_id' => \App\Models\Product::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
