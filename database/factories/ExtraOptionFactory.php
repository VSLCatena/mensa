<?php

namespace Database\Factories;

use App\Models\ExtraOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExtraOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExtraOption::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'description' => $this->faker->sentence(rand(3, 12)),
            'order' => $this->faker->numberBetween(0, 100),
            'price' => $this->faker->randomFloat(2, 0, 10),
        ];
    }
}
