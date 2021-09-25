<?php

namespace Database\Factories;

use App\Http\Controllers\Api\v1\Mensa\Models\FoodOptions;
use App\Models\Mensa;
use Illuminate\Database\Eloquent\Factories\Factory;

class MensaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Mensa::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->dateTimeBetween("-1 month", "+3 months")->getTimestamp();

        return [
            'id' => $this->faker->uuid,
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(20),
            'date' => $date,
            'closing_time' => $date,
            'max_users' => $this->faker->numberBetween(5, 50),
            'food_options' => $this->faker->numberBetween(1, 7),
            'closed' => $this->faker->boolean,
            'price' => $this->faker->randomFloat(2, 0, 10)
        ];
    }
}