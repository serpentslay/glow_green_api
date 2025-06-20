<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $personalAccessClient;

    protected function setUp(): void
    {
        parent::setUp();


        $clientRepository = new ClientRepository();
        $this->personalAccessClient = $clientRepository->createPersonalAccessGrantClient(
            "Test Access Client"
        );
    }

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'testuser@example.com']);
    }

    public function test_user_can_login_and_access_protected_route()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        // Login
        $loginResponse = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonStructure(['token']);

        $token = $loginResponse->json('token');

        // Access protected route
        $protectedResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/boilers');

        $protectedResponse->assertStatus(200);
    }
}
