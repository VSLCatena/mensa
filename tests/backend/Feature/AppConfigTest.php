<?php

namespace Tests\backend\Feature;

use Tests\TestCase;

class AppConfigTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_if_we_can_get_our_default_appconfig()
    {
        // Act
        $response = $this->getJson('/api/v1/appconfig');

        // Assert
        $response->assertSuccessful()
            ->assertJson([
                'defaultMensaOptions' => [
                    'title' => 'Mensa met betaalde afwas',
                    'maxSignups' => 42,
                    'price' => 4
                ]
            ]);
    }
}
