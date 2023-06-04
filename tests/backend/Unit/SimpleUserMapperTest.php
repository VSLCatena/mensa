<?php

namespace Tests\backend\Unit;

use App\Http\Controllers\Api\v1\Common\Mappers\SimpleUserMapper;
use App\Models\User;
use Tests\TestCase;

class SimpleUserMapperTest extends TestCase
{
    private readonly SimpleUserMapper $simpleUserMapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->simpleUserMapper = new SimpleUserMapper();
    }

    public function test_if_an_user_is_correctly_mapped()
    {
        // Arrange
        $user = User::factory()->make([
            'id' => 'id',
            'name' => 'name',
        ]);

        // Act
        $result = $this->simpleUserMapper->map($user);

        // Assert
        $this->assertEquals('id', $result->id);
        $this->assertEquals('name', $result->name);
    }
}
