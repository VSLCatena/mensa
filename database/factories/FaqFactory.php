<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Faq::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'question' => $this->faker->sentence(rand(5, 30)),
            'answer' => $this->faker->sentence(rand(15, 200)),
            'order' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
