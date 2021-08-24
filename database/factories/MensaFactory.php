<?php

namespace Database\Factories;

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
        $date = $this->faker->dateTime()->getTimestamp();

        return [
            'id' => $this->faker->uuid,
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(8),
            'date' => $date,
            'closing_time' => $date,
            'max_users' => $this->faker->numberBetween(5, 50),
            'closed' => $this->faker->boolean
        ];
    }
}