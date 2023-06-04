<?php

namespace Tests\backend\Unit;

use App\Http\Controllers\Api\v1\Common\Mappers\FoodOptionsMapper;
use Tests\TestCase;

class FoodOptionsMapperTest extends TestCase
{
    private readonly FoodOptionsMapper $foodOptionsMapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->foodOptionsMapper = new FoodOptionsMapper();
    }

    public function test_single_options_from_int_to_name()
    {
        $this->assertEquals(
            'vegan',
            $this->foodOptionsMapper->fromIntToName(1 << 0)
        );
        $this->assertEquals(
            'vegetarian',
            $this->foodOptionsMapper->fromIntToName(1 << 1)
        );
        $this->assertEquals(
            'meat',
            $this->foodOptionsMapper->fromIntToName(1 << 2)
        );
    }

    public function test_single_options_from_name_to_int()
    {
        $this->assertEquals(
            1 << 0,
            $this->foodOptionsMapper->fromNameToInt('vegan')
        );
        $this->assertEquals(
            1 << 1,
            $this->foodOptionsMapper->fromNameToInt('vegetarian')
        );
        $this->assertEquals(
            1 << 2,
            $this->foodOptionsMapper->fromNameToInt('meat')
        );
    }

    public function test_multiple_options_from_int_to_name() {
        $this->assertEquals(
            ['vegan', 'vegetarian'],
            $this->foodOptionsMapper->fromIntToNames((1 << 0) + (1 << 1))
        );
        $this->assertEquals(
            ['vegetarian', 'meat'],
            $this->foodOptionsMapper->fromIntToNames((1 << 1) + (1 << 2))
        );
        $this->assertEquals(
            ['vegan', 'meat'],
            $this->foodOptionsMapper->fromIntToNames((1 << 0) + (1 << 2))
        );
        $this->assertEquals(
            ['vegan', 'vegetarian', 'meat'],
            $this->foodOptionsMapper->fromIntToNames((1 << 0) + (1 << 1) + (1 << 2))
        );
    }

    public function test_multiple_options_from_name_to_int() {
        $this->assertEquals(
            (1 << 0) + (1 << 1),
            $this->foodOptionsMapper->fromNamesToInt(['vegan', 'vegetarian'])
        );
        $this->assertEquals(
            (1 << 1) + (1 << 2),
            $this->foodOptionsMapper->fromNamesToInt(['vegetarian', 'meat'])
        );
        $this->assertEquals(
            (1 << 0) + (1 << 2),
            $this->foodOptionsMapper->fromNamesToInt(['vegan', 'meat'])
        );
        $this->assertEquals(
            (1 << 0) + (1 << 1) + (1 << 2),
            $this->foodOptionsMapper->fromNamesToInt(['vegan', 'vegetarian', 'meat'])
        );
    }

    public function test_null_option_from_int_to_name() {
        $this->assertEquals(
            null,
            $this->foodOptionsMapper->fromIntToName(null)
        );
    }

    public function test_null_option_from_name_to_int() {
        $this->assertEquals(
            0,
            $this->foodOptionsMapper->fromNameToInt(null)
        );
    }

    public function test_invalid_option_from_int_to_name() {
        $this->assertEquals(
            null,
            $this->foodOptionsMapper->fromIntToName(1 << 3)
        );
    }

    public function test_invalid_option_from_name_to_int() {
        $this->assertEquals(
            0,
            $this->foodOptionsMapper->fromNameToInt('invalid')
        );
    }
}
