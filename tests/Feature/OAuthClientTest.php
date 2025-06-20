<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;

class OAuthClientTest extends TestCase
{


    public function test_client_can_access_protected_route()
    {
        Passport::actingAsClient(
            Client::factory()->create(),
            ['check-status']
        );

        // Use the token to access a protected route
        $protectedResponse = $this->getJson('/api/boilers');

        $protectedResponse->assertStatus(200);
    }
}
