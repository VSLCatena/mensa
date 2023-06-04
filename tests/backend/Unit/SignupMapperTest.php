<?php

namespace Tests\backend\Unit;

use App\Http\Controllers\Api\v1\Common\Mappers\FoodOptionsMapper;
use App\Http\Controllers\Api\v1\Mensa\Mappers\SignupMapper;
use App\Models\Signup;
use Tests\TestCase;

class SignupMapperTest extends TestCase
{
    private readonly SignupMapper $signupMapper;

    private readonly FoodOptionsMapper $foodOptionsMapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->foodOptionsMapper = $this->createMock(FoodOptionsMapper::class);
        $this->signupMapper = new SignupMapper($this->foodOptionsMapper);
    }

    public function test_if_a_signup_is_correctly_mapped()
    {
        // Arrange
        $signup = Signup::factory()->make([
            'id' => 'id',
            'allergies' => 'allergies',
            'extra_info' => 'extra_info',
            'food_option' => 1,
            'cooks' => true,
            'dishwasher' => true,
        ]);
        $this->foodOptionsMapper->method('fromIntToName')->willReturn('vegetarian');

        // Act
        $result = $this->signupMapper->map($signup);

        // Assert
        $this->assertEquals('id', $result->id);
        $this->assertEquals('allergies', $result->allergies);
        $this->assertEquals('extra_info', $result->extraInfo);
        $this->assertEquals('vegetarian', $result->foodOption);
        $this->assertTrue($result->cooks);
        $this->assertTrue($result->dishwasher);
    }

    public function test_if_cook_is_correctly_mapped() {
        // Arrange
        $notCook = Signup::factory()->make([ 'cooks' => false]);
        $cook = Signup::factory()->make([ 'cooks' => true]);
        $this->foodOptionsMapper->method('fromIntToName')->willReturn('vegetarian');

        // Act
        $resultNotCook = $this->signupMapper->map($notCook);
        $resultCook = $this->signupMapper->map($cook);

        // Assert
        $this->assertFalse($resultNotCook->cooks);
        $this->assertTrue($resultCook->cooks);
    }

    public function test_if_dishwashing_is_correctly_mapped() {
        // Arrange
        $notDishwasher = Signup::factory()->make([ 'dishwasher' => false]);
        $dishwasher = Signup::factory()->make([ 'dishwasher' => true]);
        $this->foodOptionsMapper->method('fromIntToName')->willReturn('vegetarian');

        // Act
        $resultNotDishwasher = $this->signupMapper->map($notDishwasher);
        $resultDishwasher = $this->signupMapper->map($dishwasher);

        // Assert
        $this->assertFalse($resultNotDishwasher->dishwasher);
        $this->assertTrue($resultDishwasher->dishwasher);
    }
}