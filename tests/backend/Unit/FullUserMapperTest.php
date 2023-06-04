<?php

namespace Tests\backend\Unit;

use App\Http\Controllers\Api\v1\Common\Mappers\FoodOptionsMapper;
use App\Http\Controllers\Api\v1\User\Mappers\FullUserMapper;
use App\Models\User;
use Tests\TestCase;

class FullUserMapperTest extends TestCase
{
    private readonly FullUserMapper $fullUserMapper;

    private readonly FoodOptionsMapper $foodOptionsMapperMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->foodOptionsMapperMock = $this->createMock(FoodOptionsMapper::class);
        $this->fullUserMapper = new FullUserMapper($this->foodOptionsMapperMock);
    }

    public function test_if_mapping_a_full_user_works_correctly()
    {
        // Arrange
        $user = User::factory()->make([
            'id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'allergies' => 'allergies',
            'extra_info' => 'extra_info',
            'food_preference' => 1,
            'mensa_admin' => true,
        ]);
        $this->foodOptionsMapperMock->method('fromIntToName')->willReturn('foodOption');

        // Act
        $result = $this->fullUserMapper->map($user);

        // Assert
        $this->assertEquals('id', $result->id);
        $this->assertEquals('name', $result->name);
        $this->assertEquals('email', $result->email);
        $this->assertEquals('allergies', $result->allergies);
        $this->assertEquals('extra_info', $result->extraInfo);
        $this->assertEquals('foodOption', $result->foodPreference);
        $this->assertTrue($result->isAdmin);
    }

    public function test_if_admin_is_correctly_mapped() {
        // Arrange
        $notAdmin = User::factory()->make([ 'mensa_admin' => false]);
        $admin = User::factory()->make([ 'mensa_admin' => true]);

        // Act
        $resultNotAdmin = $this->fullUserMapper->map($notAdmin);
        $resultAdmin = $this->fullUserMapper->map($admin);

        // Assert
        $this->assertFalse($resultNotAdmin->isAdmin);
        $this->assertTrue($resultAdmin->isAdmin);
    }
}
