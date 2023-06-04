<?php

namespace Tests\backend\Unit;

use App\Http\Controllers\Api\v1\Common\Mappers\FoodOptionsMapper;
use App\Http\Controllers\Api\v1\Common\Mappers\SimpleUserMapper;
use App\Http\Controllers\Api\v1\Common\Models\SimpleUserDto;
use App\Http\Controllers\Api\v1\Mensa\Mappers\ExtraOptionsMapper;
use App\Http\Controllers\Api\v1\Mensa\Mappers\MensaMapper;
use App\Http\Controllers\Api\v1\Mensa\Mappers\MenuItemMapper;
use App\Http\Controllers\Api\v1\Mensa\Mappers\UserSignupMapper;
use App\Http\Controllers\Api\v1\Mensa\Models\ExtraOptionDto;
use App\Http\Controllers\Api\v1\Mensa\Models\MensaUserDto;
use App\Http\Controllers\Api\v1\Mensa\Models\MensaUserWithSignupDto;
use App\Http\Controllers\Api\v1\Mensa\Models\MenuItemDto;
use App\Http\Controllers\Api\v1\Mensa\Models\SignupDto;
use App\Models\Mensa;
use App\Models\Signup;
use App\Models\SignupAndUserCombined;
use App\Models\User;
use Tests\TestCase;

class MensaMapperTest extends TestCase
{
    private readonly MensaMapper $mensaMapper;
    private readonly ExtraOptionsMapper $extraOptionsMapperMock;
    private readonly UserSignupMapper $userSignupMapperMock;
    private readonly MenuItemMapper $menuItemMapperMock;
    private readonly FoodOptionsMapper $foodOptionsMapperMock;
    private readonly SimpleUserMapper $simpleUserMapperMock;


    protected function setUp(): void
    {
        parent::setUp();$this->extraOptionsMapperMock = $this->createMock(ExtraOptionsMapper::class);
        $this->userSignupMapperMock = $this->createMock(UserSignupMapper::class);
        $this->menuItemMapperMock = $this->createMock(MenuItemMapper::class);
        $this->foodOptionsMapperMock = $this->createMock(FoodOptionsMapper::class);
        $this->simpleUserMapperMock = $this->createMock(SimpleUserMapper::class);

        $this->mensaMapper = new MensaMapper(
            $this->extraOptionsMapperMock,
            $this->userSignupMapperMock,
            $this->menuItemMapperMock,
            $this->foodOptionsMapperMock,
            $this->simpleUserMapperMock
        );
    }

    /**
     * Test if receiving a mensa as an anonymous user works correctly.
     *
     * @return void
     */
    public function test_if_mensa_is_correctly_mapped_for_anonymous_user()
    {
        // Arrange
        $user = null;
        $signupUser = User::factory()->make();
        $signupSignup = Signup::factory()->make(['dishwasher' => true, 'cooks' => true, 'is_intro' => false]);
        $signup = new SignupAndUserCombined($signupUser, $signupSignup);
        $mensa = Mensa::factory()->make([
            'id' => 'Id 1',
            'title' => 'Mensa 1',
            'description' => 'Description 1',
            'date' => 123456789,
            'closing_time' => 234567890,
            'max_signups' => 30,
            'food_options' => 1 << 0 | 1 << 1,
            'closed' => false,
            'price' => 3.5
        ]);

        $extraOptionDtos = [new ExtraOptionDto('Id 1', 'Description 1', 1.5)];
        $menuItemDtos = [new MenuItemDto('Id 1', 'Text 1')];
        $foodOptionDtos = ['foodOptions'];
        $cookDto = new SimpleUserDto('Id 1', 'User 1');

        $this->extraOptionsMapperMock->method('mapArray')->willReturn($extraOptionDtos);
        $this->userSignupMapperMock->expects($this->never())->method('map');
        $this->menuItemMapperMock->method('mapArray')->willReturn($menuItemDtos);
        $this->foodOptionsMapperMock->method('fromIntToNames')->willReturn($foodOptionDtos);
        $this->simpleUserMapperMock->method('map')->willReturn($cookDto);
        
        // Act
        $result = $this->mensaMapper->map($mensa, [$signup], [], [], $user);
        
        // Assert
        // We do this through json, so we can make sure that we can exactly define how we want it sent to frontend.
        $expected = [
            'id' => 'Id 1',
            'title' => 'Mensa 1',
            'description' => 'Description 1',
            'date' => 123456789,
            'closingTime' => 234567890,
            'isClosed' => false,
            'maxSignups' => 30,
            'signups' => 1,
            'price' => 3.5,
            'dishwashers' => 1,
            'cooks' => [
                [
                    'id' => 'Id 1',
                    'name' => 'User 1'
                ]
            ],
            'foodOptions' => ['foodOptions'],
            'menu' => [
                [
                    'id' => 'Id 1',
                    'text' => 'Text 1'
                ]
            ],
            'extraOptions' => [
                [
                    'id' => 'Id 1',
                    'description' => 'Description 1',
                    'price' => 1.5
                ]
            ]
        ];
        $this->assertEquals(json_encode($expected, JSON_PRETTY_PRINT), json_encode($result, JSON_PRETTY_PRINT));
    }

    /**
     * Test if receiving a mensa as a logged-in user works correctly.
     */
    public function test_if_mensa_is_correctly_mapped_as_a_logged_in_user() {
        // Arrange
        $user = User::factory()->make(['mensa_admin' => false]);
        $signup = new SignupAndUserCombined(
            User::factory()->make(),
            Signup::factory()->make(['dishwasher' => true, 'cooks' => true])
        );
        $mensa = Mensa::factory()->make([
            'id' => 'Id 1',
            'title' => 'Mensa 1',
            'description' => 'Description 1',
            'date' => 123456789,
            'closing_time' => 234567890,
            'max_signups' => 30,
            'food_options' => 1 << 0 | 1 << 1,
            'closed' => false,
            'price' => 3.5
        ]);

        $extraOptionDtos = [new ExtraOptionDto('Id 1', 'Description 1', 1.5)];
        $menuItemDtos = [new MenuItemDto('Id 1', 'Text 1')];
        $userDto = new MensaUserDto('Id 1', 'User 1', false);
        $foodOptionDtos = ['foodOptions'];
        $cookDto = new SimpleUserDto('Id 1', 'User 1');

        $this->extraOptionsMapperMock->method('mapArray')->willReturn($extraOptionDtos);
        // We make sure that the last argument called is false, because we are not an admin.
        $this->userSignupMapperMock
            ->expects($this->once())
            ->method('map')
            ->with($signup->user, $signup->signup, false)
            ->willReturn($userDto);
        $this->menuItemMapperMock->method('mapArray')->willReturn($menuItemDtos);
        $this->foodOptionsMapperMock->method('fromIntToNames')->willReturn($foodOptionDtos);
        $this->simpleUserMapperMock->method('map')->willReturn($cookDto);

        // Act
        $result = $this->mensaMapper->map($mensa, [$signup], [], [], $user);

        // Assert
        // We do this through json, so we can make sure that we can exactly define how we want it sent to frontend.
        $expected = [
            'id' => 'Id 1',
            'title' => 'Mensa 1',
            'description' => 'Description 1',
            'date' => 123456789,
            'closingTime' => 234567890,
            'isClosed' => false,
            'maxSignups' => 30,
            'signups' => [
                [
                    'id'=> 'Id 1',
                    'name' => 'User 1',
                    'isIntro' => false
                ]
            ],
            'price' => 3.5,
            'dishwashers' => 1,
            'cooks' => [
                [
                    'id' => 'Id 1',
                    'name' => 'User 1'
                ]
            ],
            'foodOptions' => ['foodOptions'],
            'menu' => [
                [
                    'id' => 'Id 1',
                    'text' => 'Text 1'
                ]
            ],
            'extraOptions' => [
                [
                    'id' => 'Id 1',
                    'description' => 'Description 1',
                    'price' => 1.5
                ]
            ]
        ];
        $this->assertEquals(json_encode($expected, JSON_PRETTY_PRINT), json_encode($result, JSON_PRETTY_PRINT));
    }

    /**
     * Test if receiving a mensa as an admin works correctly.
     */
    public function test_if_mensa_is_correctly_mapped_as_an_admin() {
        // Arrange
        $user = User::factory()->make(['mensa_admin' => true]);
        $signup = new SignupAndUserCombined(
            User::factory()->make(),
            Signup::factory()->make(['dishwasher' => false, 'cooks' => false])
        );
        $mensa = Mensa::factory()->make([
            'id' => 'Id 1',
            'title' => 'Mensa 1',
            'description' => 'Description 1',
            'date' => 123456789,
            'closing_time' => 234567890,
            'max_signups' => 30,
            'food_options' => 1 << 0 | 1 << 1,
            'closed' => false,
            'price' => 3.5
        ]);

        $extraOptionDtos = [new ExtraOptionDto('Id 1', 'Description 1', 1.5)];
        $menuItemDtos = [new MenuItemDto('Id 1', 'Text 1')];
        $signupDto = new SignupDto('Id 1', 'Allergies 1', 'Extra info 1', 'vegetarian', false, true);
        $userDto = new MensaUserWithSignupDto('Id 1', 'User 1', false, $signupDto);
        $foodOptionDtos = ['foodOptions'];
        $cookDto = new SimpleUserDto('Id 1', 'User 1');

        $this->extraOptionsMapperMock->method('mapArray')->willReturn($extraOptionDtos);
        // We make sure that the last argument called is true, because we are an admin.
        $this->userSignupMapperMock
            ->expects($this->once())
            ->method('map')
            ->with($signup->user, $signup->signup, true)
            ->willReturn($userDto);
        $this->menuItemMapperMock->method('mapArray')->willReturn($menuItemDtos);
        $this->foodOptionsMapperMock->method('fromIntToNames')->willReturn($foodOptionDtos);
        $this->simpleUserMapperMock->method('map')->willReturn($cookDto);

        // Act
        $result = $this->mensaMapper->map($mensa, [$signup], [], [], $user);

        // Assert
        // We do this through json, so we can make sure that we can exactly define how we want it sent to frontend.
        $expected = [
            'id' => 'Id 1',
            'title' => 'Mensa 1',
            'description' => 'Description 1',
            'date' => 123456789,
            'closingTime' => 234567890,
            'isClosed' => false,
            'maxSignups' => 30,
            'signups' => [
                [
                    'id'=> 'Id 1',
                    'name' => 'User 1',
                    'isIntro' => false,
                    'signup' => [
                        'id' => 'Id 1',
                        'allergies' => 'Allergies 1',
                        'extraInfo' => 'Extra info 1',
                        "foodOption" => 'vegetarian',
                        'cooks' => false,
                        'dishwasher' => true
                    ]
                ]
            ],
            'price' => 3.5,
            'dishwashers' => 0,
            'cooks' => [],
            'foodOptions' => ['foodOptions'],
            'menu' => [
                [
                    'id' => 'Id 1',
                    'text' => 'Text 1'
                ]
            ],
            'extraOptions' => [
                [
                    'id' => 'Id 1',
                    'description' => 'Description 1',
                    'price' => 1.5
                ]
            ]
        ];
        $this->assertEquals(json_encode($expected, JSON_PRETTY_PRINT), json_encode($result, JSON_PRETTY_PRINT));
    }
}
