<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'allergies' => $this->faker->boolean ? $this->faker->sentence(10) : null,
            'extra_info' => $this->faker->boolean ? $this->faker->sentence(10) : null,
            'mensa_admin' => $this->faker->boolean,
            'vegetarian' => $this->faker->boolean,
            'remote_last_check' => $this->faker->dateTime->getTimestamp(),
            'remote_principal_name' => ''
        ];
    }
}