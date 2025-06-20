<?php

namespace Tests\Feature;

use App\Models\Boiler;
use App\Models\FuelType;
use App\Models\Manufacturer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoilerApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and authenticate with Passport or Sanctum
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');
    }

    public function test_create_boiler()
    {
        $manufacturer = Manufacturer::factory()->create();
        $fuelType = FuelType::factory()->create();

        $payload = [
            'sku' => 'SKU-123',
            'boiler_manufacturer_id' => $manufacturer->id,
            'name' => 'Test Boiler',
            'description' => 'Test Description',
            'manufacturer_part_number' => 'MPN-001',
            'fuel_type_id' => $fuelType->id,
            'url' => 'https://example.com/boiler',
        ];

        $response = $this->postJson('/api/boilers', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Test Boiler']);
    }

    public function test_list_boilers()
    {
        Boiler::factory()->count(3)->create();

        $response = $this->getJson('/api/boilers');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_show_single_boiler()
    {
        $boiler = Boiler::factory()->create();

        $response = $this->getJson("/api/boilers/{$boiler->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $boiler->id]);
    }

    public function test_update_boiler()
    {
        $boiler = Boiler::factory()->create();

        $response = $this->putJson("/api/boilers/{$boiler->id}", [
            'name' => 'Updated Boiler Name',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Boiler Name']);
    }

    public function test_soft_delete_boiler()
    {
        $boiler = Boiler::factory()->create();

        $response = $this->deleteJson("/api/boilers/{$boiler->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('boilers', ['id' => $boiler->id]);
    }
}
