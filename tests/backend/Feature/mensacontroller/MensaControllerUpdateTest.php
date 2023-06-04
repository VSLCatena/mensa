<?php


namespace Tests\backend\Feature\mensacontroller;

use App\Contracts\RemoteUserLookup;
use App\Models\ExtraOption;
use App\Models\Mensa;
use App\Models\MenuItem;
use App\Models\Signup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\CreatesApplication;
use Tests\TestCase;

class MensaControllerUpdateTest extends TestCase
{
    use CreatesApplication, RefreshDatabase;

    private readonly RemoteUserLookup $remoteUserLookup;

    protected function setUp(): void
    {
        parent::setUp();
        $seeder = new \DatabaseSeeder();

        $seeder->run(
            userCount: 10,
            mensaCount: 5,
            maxMenuItemPerMensaCount: 4,
            maxExtraOptionPerMensaCount: 4,
            maxSignupPerMensaCount: 6,
            faqCount: 0,
            enforceAtLeastOne: true
        );

        $this->remoteUserLookup = $this->createMock(RemoteUserLookup::class);

        $this->instance(RemoteUserLookup::class, $this->remoteUserLookup);
    }


    public function test_if_we_can_update_a_mensa_as_admin()
    {
        // Arrange
        $user = User::factory()->create(['mensa_admin' => true]);
        $mensa = Mensa::first();
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        $this->assertDatabaseCount(Mensa::class, 5);

        // Act
        $response = $this->actingAs($user)
            ->patchJson('/api/v1/mensa/' . $mensa->id, [
                'title' => 'new title',
                'description' => 'new description',
            ]);

        // Assert
        $response->assertSuccessful();
        $this->assertDatabaseCount(Mensa::class, 5);
        $this->assertDatabaseHas(Mensa::class, [
            'id' => $mensa->id,
            'title' => 'new title',
            'description' => 'new description',
        ]);
    }

    public function test_if_we_pass_a_menu_to_the_request_we_update_and_delete_our_previous_items() {
        // Arrange
        $user = User::factory()->create(['mensa_admin' => true]);
        $mensa = Mensa::first();
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        

        // Act
        $response = $this->actingAs($user)
            ->patchJson('/api/v1/mensa/' . $mensa->id, [
                'title' => 'new title',
                'description' => 'new description',
                'menu' => [
                    [
                        'title' => 'new title',
                        'description' => 'new description',
                        'price' => 1.0,
                        'extraOptions' => [
                            [
                                'title' => 'new title',
                                'description' => 'new description',
                                'price' => 1.0,
                            ]
                        ],
                        'signups' => [
                            [
                                'title' => 'new title',
                                'description' => 'new description',
                                'price' => 1.0,
                            ]
                        ]
                    ]
                ]
            ]);

        // Assert
        $response->assertSuccessful();
        $this->assertDatabaseCount(Mensa::class, 5);
        $this->assertDatabaseCount(MenuItem::class, 1);
        $this->assertDatabaseCount(ExtraOption::class, 1);
        $this->assertDatabaseCount(Signup::class, 1);
    }

    /**
     * @dataProvider nonAdminUsers
     */
    public function test_to_make_sure_we_cannot_update_a_mensa_as_normal_user_or_anonymous(?User $user)
    {
        // Arrange
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        $this->assertDatabaseCount(Mensa::class, 5);
        $mensa = Mensa::first();

        // Act
        $actAs = $user ? $this->actingAs($user) : $this;
        $response = $actAs
            ->patchJson('/api/v1/mensa/' . $mensa->id, [
                'title' => 'new title',
                'description' => 'new description',
            ]);

        // Assert
        $response->assertForbidden();
        $this->assertDatabaseCount(Mensa::class, 5);
    }

    public function test_to_make_sure_we_can_update_specific_fields_as_cook()
    {
        // Arrange
        $this->assertDatabaseCount(Mensa::class, 5);
        $mensa = Mensa::first();
        $user = new User([
            'id' => Str::uuid(),
            'name' => 'test',
            'email' => 'test@test.test',
            'remote_principal_name' => 'test',
            'remote_last_check' => now(),
            'mensa_admin' => false,
        ]);
        $user->save();
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        Signup::whereUserId($user->id)->delete();
        $signup = new Signup([
            'signup_id' => Str::uuid(),
            'user_id' => $user->id,
            'mensa_id' => $mensa->id,
            'cooks' => true,
            'dishwasher' => false,
            'food_option' => 1,
            'is_intro' => false,
            'confirmed' => true,
            'confirmation_code' => Str::uuid(),
        ]);
        $signup->save();

        // Act
        $actAs = $user ? $this->actingAs($user) : $this;
        $response = $actAs
            ->patchJson('/api/v1/mensa/' . $mensa->id, [
                'title' => 'new title',
                'description' => 'new description',
            ]);

        // Assert
        $response->assertSuccessful();
        $this->assertDatabaseCount(Mensa::class, 5);
        $this->assertDatabaseHas(Mensa::class, [
            'id' => $mensa->id,
            'title' => 'new title',
            'description' => 'new description',
        ]);
    }

    /**
     * @dataProvider hardMensaFields
     */
    public function test_to_make_sure_that_cooks_cannot_edit_everything($hardField, $data)  {
        // Arrange
        $this->assertDatabaseCount(Mensa::class, 5);
        $mensa = Mensa::first();
        $user = new User([
            'id' => Str::uuid(),
            'name' => 'test',
            'email' => 'test@test.test',
            'remote_principal_name' => 'test',
            'remote_last_check' => now(),
            'mensa_admin' => false,
        ]);
        $user->save();
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        Signup::whereUserId($user->id)->delete();
        $signup = new Signup([
            'signup_id' => Str::uuid(),
            'user_id' => $user->id,
            'mensa_id' => $mensa->id,
            'cooks' => true,
            'dishwasher' => false,
            'food_option' => 1,
            'is_intro' => false,
            'confirmed' => true,
            'confirmation_code' => Str::uuid(),
        ]);
        $signup->save();

        // Act
        $actAs = $user ? $this->actingAs($user) : $this;
        $response = $actAs
            ->patchJson('/api/v1/mensa/' . $mensa->id, [
                'title' => 'new title',
                'description' => 'new description',
                $hardField => $data
            ]);

        // Assert
        $response->assertForbidden();
        $this->assertDatabaseCount(Mensa::class, 5);
        $this->assertDatabaseMissing(Mensa::class, [
            'id' => $mensa->id,
            'title' => 'new title',
            'description' => 'new description',
        ]);
    }


    public static function nonAdminUsers()
    {
        return [
            [new User(['mensa_admin' => false])],
            [null]
        ];
    }

    public static function hardMensaFields() {
        return [
            ['price', 3.5],
            ['date', 123456],
            ['closingTime', 123456],
            ['maxSignups', 50],
            ['closed', true]
        ];
    }
}
