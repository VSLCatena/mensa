<?php

namespace Tests\backend\Unit;

use App\Http\Controllers\Api\v1\Mensa\Mappers\SignupMapper;
use App\Http\Controllers\Api\v1\Mensa\Mappers\UserSignupMapper;
use App\Http\Controllers\Api\v1\Mensa\Models\MensaUserDto;
use App\Http\Controllers\Api\v1\Mensa\Models\MensaUserWithSignupDto;
use App\Http\Controllers\Api\v1\Mensa\Models\SignupDto;
use App\Models\Signup;
use App\Models\User;
use Tests\TestCase;

class UserSignupMapperTest extends TestCase
{
    private readonly UserSignupMapper $userSignupMapper;

    private readonly SignupMapper $signupMapperMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signupMapperMock = $this->createMock(SignupMapper::class);
        $this->userSignupMapper = new UserSignupMapper($this->signupMapperMock);
    }

    public function test_if_a_signup_is_correctly_mapped()
    {
        // Arrange
        $user = User::factory()->make([
            'id' => 'id',
            'name' => 'name',
        ]);
        $signup = Signup::factory()->make([
            'is_intro' => true
        ]);
        $signupDto = new SignupDto('id', null, null, 1, false, false);
        $this->signupMapperMock->method('map')->willReturn($signupDto);

        // Act
        $result = $this->userSignupMapper->map($user, $signup, true);

        // Assert
        $this->assertEquals('id', $result->id);
        $this->assertEquals('name', $result->name);
        $this->assertTrue($result->isIntro);
        $this->assertNotNull($result->signup);
        $this->assertEquals($signupDto, $result->signup);
        $this->assertInstanceOf(MensaUserWithSignupDto::class, $result);
    }

    public function test_if_intro_correctly_switches() {
        // Arrange
        $user = User::factory()->make();
        $intro = Signup::factory()->make([
            'is_intro' => true
        ]);
        $notIntro = Signup::factory()->make([
            'is_intro' => false
        ]);

        // Act
        $resultIntro = $this->userSignupMapper->map($user, $intro, false);
        $resultNotIntro = $this->userSignupMapper->map($user, $notIntro, false);

        // Assert
        $this->assertTrue($resultIntro->isIntro);
        $this->assertFalse($resultNotIntro->isIntro);
        $this->assertInstanceOf(MensaUserDto::class, $resultIntro);
        $this->assertNotInstanceOf(MensaUserWithSignupDto::class, $resultIntro);
    }
}