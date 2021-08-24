<?php

namespace Database\Factories;

use App\Models\Signup;
use Illuminate\Database\Eloquent\Factories\Factory;

class SignupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Signup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'cooks' => $this->faker->boolean,
            'dishwasher' => $this->faker->boolean,
            'vegetarian' => $this->faker->boolean,
            'is_intro' => $this->faker->boolean,
            'allergies' => $this->faker->boolean ? $this->faker->sentence(10) : null,
            'extra_info' => $this->faker->boolean ? $this->faker->sentence(10) : null,
            'confirmed' => $this->faker->boolean,
            'paid' => $this->faker->randomFloat(2, 0, 25),
            'confirmation_code' => $this->faker->uuid
        ];
    }
}