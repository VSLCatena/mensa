<?php


namespace Tests\backend\Feature\mensacontroller;

use App\Contracts\RemoteUserLookup;
use App\Models\ExtraOption;
use App\Models\Mensa;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestCase;

class MensaControllerCreateTest extends TestCase
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

    public function test_if_we_can_create_a_mensa_as_admin()
    {
        // Arrange
        $user = User::factory()->create(['mensa_admin' => true]);
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        $this->assertDatabaseCount(Mensa::class, 5);

        // Act
        $response = $this->actingAs($user)
            ->postJson('/api/v1/mensa/new', [
                'title' => 'test title',
                'description' => 'test description',
                'date' => 1672570800, // 2023-01-01 12:00:00
                'closingTime' => 1672574400, // 2023-01-01 13:00:00
                'maxSignups' => 10,
                'price' => 5.0,
                'foodOptions' => [
                    'vegan', 'vegetarian'
                ],
                'menu' => [
                    [
                        'text' => 'test menu item'
                    ]
                ],
                'extraOptions' => [
                    [
                        'description' => 'test extra option',
                        'price' => 1.0
                    ]
                ]
            ]);

        // Assert
        $response->assertSuccessful();
        $this->assertDatabaseCount(Mensa::class, 6);
        $mensaId = Mensa::where('date', '=', 1672570800)
            ->where('closing_time', '=', 1672574400)
            ->first()->id;
        $this->assertDatabaseHas(Mensa::class, [
            'id' => $mensaId,
            'title' => 'test title',
            'description' => 'test description',
            'date' => 1672570800,
            'closing_time' => 1672574400,
            'food_options' => 3,
            'max_signups' => 10,
            'price' => 5.0,
        ]);
        $this->assertDatabaseHas(MenuItem::class, [
            'mensa_id' => $mensaId,
            'text' => 'test menu item'
        ]);
        $this->assertDatabaseHas(ExtraOption::class, [
            'mensa_id' => $mensaId,
            'description' => 'test extra option',
            'price' => 1.0
        ]);
    }

    /**
     * @dataProvider nonAdminUsers
     */
    public function test_to_make_sure_we_cannot_create_a_mensa_as_normal_user_or_anonymous(?User $user)
    {
        // Arrange
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        $this->assertDatabaseCount(Mensa::class, 5);

        // Act
        $actAs = $user ? $this->actingAs($user) : $this;
        $response = $actAs
            ->postJson('/api/v1/mensa/new', [
                'title' => 'test title',
                'description' => 'test description',
                'date' => 1672570800, // 2023-01-01 12:00:00
                'closingTime' => 1672574400, // 2023-01-01 13:00:00
                'maxSignups' => 10,
                'price' => 5.0,
                'foodOptions' => [
                    'vegan', 'vegetarian'
                ],
                'menu' => [
                    [
                        'text' => 'test menu item'
                    ]
                ],
                'extraOptions' => [
                    [
                        'description' => 'test extra option',
                        'price' => 1.0
                    ]
                ]
            ]);

        // Assert
        $response->assertForbidden();
        $this->assertDatabaseCount(Mensa::class, 5);
    }

    public function test_to_make_sure_we_cannot_create_a_mensa_with_missing_data()
    {
        // Arrange
        $user = User::factory()->create(['mensa_admin' => true]);
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        $this->assertDatabaseCount(Mensa::class, 5);

        // Act
        // missing title
        $response = $this->actingAs($user)
            ->postJson('/api/v1/mensa/new', [
                'description' => 'test description',
                'date' => 1672570800, // 2023-01-01 12:00:00
                'closingTime' => 1672574400, // 2023-01-01 13:00:00
                'maxSignups' => 10,
                'price' => 5.0,
                'foodOptions' => [
                    'vegan', 'vegetarian'
                ],
                'menu' => [
                    [
                        'text' => 'test menu item'
                    ]
                ],
                'extraOptions' => [
                    [
                        'description' => 'test extra option',
                        'price' => 1.0
                    ]
                ]
            ]);

        // Assert
        $response->assertStatus(400);
        $this->assertDatabaseCount(Mensa::class, 5);
    }


    public static function nonAdminUsers()
    {
        return [
            [new User(['mensa_admin' => false])],
            [null]
        ];
    }
}
