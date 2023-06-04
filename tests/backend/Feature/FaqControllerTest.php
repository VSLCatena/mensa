<?php

namespace Tests\backend\Feature;

use App\Contracts\RemoteUserLookup;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestCase;

class FaqControllerTest extends TestCase
{
    use CreatesApplication, RefreshDatabase;

    private readonly RemoteUserLookup $remoteUserLookup;

    protected function setUp(): void
    {
        parent::setUp();
        $seeder = new \DatabaseSeeder();

        $seeder->run(
            userCount: 0,
            mensaCount: 0,
            maxMenuItemPerMensaCount: 0,
            maxExtraOptionPerMensaCount: 0,
            maxSignupPerMensaCount: 0,
            faqCount: 5
        );

        $this->remoteUserLookup = $this->createMock(RemoteUserLookup::class);

        $this->instance(RemoteUserLookup::class, $this->remoteUserLookup);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_if_we_can_get_a_list_of_faqs()
    {
        // Act
        $response = $this->getJson('/api/v1/faqs');

        // Assert
        $response->assertSuccessful()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'question',
                    'answer',
                ]
            ]);
    }

    public function test_if_we_can_create_a_faq_as_an_admin() {
        // Arrange
        $this->assertDatabaseCount(Faq::class, 5);
        $user = User::factory()->create(['mensa_admin' => true]);
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);

        // Act
        $response = $this->actingAs($user)
            ->postJson('/api/v1/faq/new', [
                'question' => 'test question',
                'answer' => 'test answer',
            ]);

        // Assert
        $response->assertSuccessful();
        $this->assertDatabaseCount(Faq::class, 6);
        $this->assertDatabaseHas(Faq::class, [
            'question' => 'test question',
            'answer' => 'test answer',
        ]);
    }

    public function test_if_we_block_faulty_requests_on_creating_a_faq() {
        // Arrange
        $this->assertDatabaseCount(Faq::class, 5);
        $user = User::factory()->create(['mensa_admin' => true]);
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);

        // Act
        $response = $this->actingAs($user)
            ->postJson('/api/v1/faq/new', [
                'question' => 'test question',
            ]);

        // Assert
        $response->assertStatus(400);
        $this->assertDatabaseCount(Faq::class, 5);
        $this->assertDatabaseMissing(Faq::class, [
            'question' => 'test question',
        ]);
    }

    /**
     * @dataProvider nonAdminUsers
     */
    public function test_if_we_cannot_create_a_faq_as_a_user(?User $user) {
        // Arrange
        $this->assertDatabaseCount(Faq::class, 5);
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);

        // Act
        $actAs = $user ? $this->actingAs($user) : $this;
        $response = $actAs
            ->postJson('/api/v1/faq/new', [
                'question' => 'test question',
                'answer' => 'test answer',
            ]);

        // Assert
        $response->assertStatus(403);
        $this->assertDatabaseCount(Faq::class, 5);
        $this->assertDatabaseMissing(Faq::class, [
            'question' => 'test question',
            'answer' => 'test answer',
        ]);
    }

    public function test_if_we_can_delete_a_faq_as_admin() {
        // Arrange
        $user = User::factory()->create(['mensa_admin' => true]);
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        $this->assertDatabaseCount(Faq::class, 5);
        $faq = Faq::first();

        // Act
        $response = $this->actingAs($user)
            ->deleteJson('/api/v1/faq/' . $faq->id);

        // Assert
        $response->assertSuccessful();
        $this->assertDatabaseCount(Faq::class, 4);
        $this->assertModelMissing($faq);
    }

    public function test_if_we_block_faulty_requests_on_deleting_a_faq() {
        // Arrange
        $user = User::factory()->create(['mensa_admin' => true]);
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        $this->assertDatabaseCount(Faq::class, 5);

        // Act
        $response = $this->actingAs($user)
            ->deleteJson('/api/v1/faq/non-existing-id');

        // Assert
        $response->assertStatus(404);
        $this->assertDatabaseCount(Faq::class, 5);
    }

    /**
     * @dataProvider nonAdminUsers
     */
    public function test_if_we_cannot_delete_a_faq_as_non_admin(?User $user) {
        // Arrange
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        $this->assertDatabaseCount(Faq::class, 5);
        $faq = Faq::first();

        // Act
        $actAs = $user ? $this->actingAs($user) : $this;
        $response = $actAs
            ->deleteJson('/api/v1/faq/' . $faq->id);

        // Assert
        $response->assertStatus(403);
        $this->assertDatabaseCount(Faq::class, 5);
        $this->assertModelExists($faq);
    }

    public function test_if_we_can_update_a_faq_as_admin() {
        // Arrange
        $user = User::factory()->create(['mensa_admin' => true]);
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        $this->assertDatabaseCount(Faq::class, 5);
        $faq = Faq::first();

        // Act
        $response = $this->actingAs($user)
            ->putJson('/api/v1/faq/' . $faq->id, [
                'question' => 'test question',
                'answer' => 'test answer',
            ]);

        // Assert
        $response->assertSuccessful();
        $this->assertDatabaseCount(Faq::class, 5);
        $this->assertDatabaseHas(Faq::class, [
            'id' => $faq->id,
            'question' => 'test question',
            'answer' => 'test answer',
        ]);
    }

    public function test_if_we_block_faulty_requests_on_updating_a_faq_not_existing() {
        // Arrange
        $user = User::factory()->create(['mensa_admin' => true]);
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        $this->assertDatabaseCount(Faq::class, 5);

        // Act
        $response = $this->actingAs($user)
            ->putJson('/api/v1/faq/non-existing-id', [
                'question' => 'test question',
                'answer' => 'test answer',
            ]);

        // Assert
        $response->assertStatus(404);
        $this->assertDatabaseCount(Faq::class, 5);
    }

    public function test_if_we_block_faulty_requests_on_updating_a_faq() {
        // Arrange
        $user = User::factory()->create(['mensa_admin' => true]);
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        $this->assertDatabaseCount(Faq::class, 5);
        $faq = Faq::first();

        // Act
        $response = $this->actingAs($user)
            ->putJson('/api/v1/faq/' . $faq->id, [
                'question' => 'test question',
            ]);

        // Assert
        $response->assertStatus(400);
        $this->assertDatabaseCount(Faq::class, 5);
    }

    /**
     * @dataProvider nonAdminUsers
     */
    public function test_if_we_cannot_update_a_faq_as_non_admin(?User $user) {
        // Arrange
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        $this->assertDatabaseCount(Faq::class, 5);
        $faq = Faq::first();

        // Act
        $actAs = $user ? $this->actingAs($user) : $this;
        $response = $actAs
            ->putJson('/api/v1/faq/' . $faq->id, [
                'question' => 'test question',
                'answer' => 'test answer',
            ]);

        // Assert
        $response->assertStatus(403);
        $this->assertDatabaseCount(Faq::class, 5);
        $this->assertDatabaseMissing(Faq::class, [
            'id' => $faq->id,
            'question' => 'test question',
            'answer' => 'test answer',
        ]);
    }

    public function test_if_we_can_update_order_as_an_admin() {
        // Arrange
        $user = User::factory()->create(['mensa_admin' => true]);
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn($user);
        $this->assertDatabaseCount(Faq::class, 5);
        $faqs = Faq::orderBy('order')->get();
        $faqOrder = $faqs->pluck('id')->toArray();

        // Act
        $response = $this->actingAs($user)
            ->postJson('/api/v1/faqs/sort', [
                'ids' => $faqs->pluck('id')->reverse()->toArray(),
            ]);

        // Assert
        $response->assertSuccessful();
        $this->assertDatabaseCount(Faq::class, 5);

        $lastFaq = Faq::orderBy('order', 'desc')->first();
        $this->assertEquals($faqOrder[0], $lastFaq->id);
        foreach($faqOrder as $index => $faqId) {
            $this->assertDatabaseHas(Faq::class, [
                'id' => $faqId,
                'order' => 4 - $index,
            ]);
        }
    }

    /**
     * @dataProvider nonAdminUsers
     */
    public function test_if_we_cannot_update_order_as_a_non_admin(?User $user) {
        // Arrange
        $this->remoteUserLookup->method('currentUpdatedIfNecessary')->willReturn(null);
        $this->assertDatabaseCount(Faq::class, 5);
        $faqs = Faq::orderBy('order')->get();
        $faqOrder = $faqs->pluck('id')->toArray();

        // Act
        $actAs = $user ? $this->actingAs($user) : $this;
        $response = $actAs
            ->postJson('/api/v1/faqs/sort', [
                'ids' => $faqs->pluck('id')->reverse()->toArray(),
            ]);

        // Assert
        $response->assertStatus(403);
        $this->assertDatabaseCount(Faq::class, 5);
        $firstFaq = Faq::orderBy('order')->first();
        $this->assertEquals($faqOrder[0], $firstFaq->id);
    }


    public static function nonAdminUsers() {
        return [
            [ new User(['mensa_admin' => false]) ],
            [ null ]
        ];
    }
}
