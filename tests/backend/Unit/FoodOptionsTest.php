<?php

namespace Tests\backend\Unit;

use App\Http\Controllers\Api\v1\Common\Models\FoodOption;
use Tests\TestCase;

class FoodOptionsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_if_all_names_are_correctly_returned()
    {
        $this->assertEquals(
            ['vegan', 'vegetarian', 'meat'],
            FoodOption::allNames()
        );
    }
}
