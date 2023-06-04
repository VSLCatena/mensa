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

class MensaControllerGetTest extends TestCase
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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_if_we_can_get_a_mensa_from_an_id()
    {
        // Arrange
        $mensa = Mensa::first();

        // Act
        $response = $this->getJson('/api/v1/mensa/' . $mensa->id);

        // Assert
        $response->assertSuccessful()
            ->assertJsonStructure([
                'id',
                'title',
                'description',
                'date',
                'closingTime',
                'isClosed',
                'maxSignups',
                'signups',
                'price',
                'dishwashers',
                'cooks' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ],
                'foodOptions',
                'menu' => [
                    '*' => [
                        'id',
                        'text'
                    ]
                ],
                'extraOptions' => [
                    '*' => [
                        'id',
                        'description',
                        'price'
                    ]
                ]
            ]);
    }

    public function test_if_we_return_a_404_if_a_mensa_does_not_exist()
    {
        // Act
        $response = $this->getJson('/api/v1/mensa/non-existing-id');

        // Assert
        $response->assertNotFound();
    }

    public function test_if_we_get_signup_count_of_a_mensa_if_not_logged_in()
    {
        // Arrange
        $mensa = Mensa::first();
        $signupCount = $mensa->signups()->count();

        // Act
        $response = $this->getJson('/api/v1/mensa/' . $mensa->id);

        // Assert
        $response
            ->assertSuccessful()
            ->assertJsonPath('signups', $signupCount);
    }

    public function test_if_we_get_names_when_logged_in()
    {
        // Arrange
        $mensa = Mensa::first();
        $user = User::factory()->create(['mensa_admin' => false]);
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);

        // Act
        $response = $this->actingAs($user)
            ->getJson('/api/v1/mensa/' . $mensa->id);

        // Assert
        $response
            ->assertSuccessful()
            ->assertJsonStructure([
                'signups' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ],
            ]);
    }

    public function test_if_we_get_complete_signup_if_logged_in_as_admin()
    {
        // Arrange
        $mensa = Mensa::first();
        $user = User::factory()->create(['mensa_admin' => true]);
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);

        // Act
        $response = $this->actingAs($user)
            ->getJson('/api/v1/mensa/' . $mensa->id);

        // Assert
        $response
            ->assertSuccessful()
            ->assertJsonStructure([
                'signups' => [
                    '*' => [
                        'signup' => [
                            'id',
                            'allergies',
                            'extraInfo',
                            'foodOption',
                            'cooks',
                            'dishwasher'
                        ]
                    ]
                ],
            ]);
    }
}
