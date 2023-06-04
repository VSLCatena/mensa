<?php

namespace Tests\backend\Feature;

use App\Contracts\RemoteUserLookup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestCase;

class SelfControllerTest extends TestCase
{
    use CreatesApplication, RefreshDatabase;

    private readonly RemoteUserLookup $remoteUserLookup;

    protected function setUp(): void
    {
        parent::setUp();
        $seeder = new \DatabaseSeeder();

        $seeder->run(
            userCount: 5,
            mensaCount: 0,
            maxMenuItemPerMensaCount: 0,
            maxExtraOptionPerMensaCount: 0,
            maxSignupPerMensaCount: 0,
            faqCount: 0
        );

        $this->remoteUserLookup = $this->createMock(RemoteUserLookup::class);

        $this->instance(RemoteUserLookup::class, $this->remoteUserLookup);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_if_a_logged_in_user_get_their_info()
    {
        // Arrange
        $user = User::first();

        // Act
        $response = $this->actingAs($user)
            ->getJson('/api/v1/user/self');

        // Assert
        // Log in console
        $response->assertSuccessful()
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'allergies',
                'extraInfo',
                'foodPreference',
                'isAdmin'
            ]);
    }

    public function test_if_a_guest_cannot_get_any_info() {
        // Act
        $response = $this->getJson('/api/v1/user/self');

        // Assert
        $response->assertUnauthorized();
    }

    public function test_if_a_logged_in_user_can_update_their_info()
    {
        // Arrange
        $user = User::first();

        // Act
        $response = $this->actingAs($user)
            ->patchJson('/api/v1/user/self', [
                'allergies' => 'New allergies',
                'extraInfo' => 'New extra info',
                'foodPreference' => 'vegetarian',
            ]);

        // Assert
        $response->assertSuccessful();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'allergies' => 'New allergies',
            'extra_info' => 'New extra info',
            'food_preference' => 2,
        ]);
    }

    public function test_if_a_guest_cannot_update_any_info() {
        // Act
        $response = $this
            ->patchJson('/api/v1/user/self', [
                'allergies' => 'New allergies',
                'extraInfo' => 'New extra info',
                'foodPreference' => 'vegetarian',
            ]);

        // Assert
        $response->assertUnauthorized();
    }
}
