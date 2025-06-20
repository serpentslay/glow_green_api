<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class BoilerSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_boiler_seeder_populates_database()
    {
        // Fake HTTP response
        Http::fake([
            'https://api.glowgreenltd.com/*' => Http::response([
                'data' => [
                    [
                        "id" => 1,
                        "sku" => "ABC123",
                        "boiler_manufacturer_id" => 1,
                        "name" => "Test Boiler",
                        "description" => "Test desc",
                        "manufacturer_part_number" => "MPN1",
                        "fuel_type_id" => 1,
                        "url" => "http://example.com",
                        "boiler_manufacturer" => [
                            "id" => 1,
                            "name" => "test-manufacturer",
                            "location_id" => null,
                            "created_at" => now(),
                            "updated_at" => now(),
                            "deleted_at" => null
                        ],
                        "fuel_type" => [
                            "id" => 1,
                            "fuel_type_ref" => "gas",
                            "name" => "Gas"
                        ]
                    ]
                ]
            ], 200),
        ]);

        $this->artisan('db:seed', ['--class' => 'BoilerSeeder'])
            ->assertExitCode(0);

        $this->assertDatabaseHas('boilers', [
            'name' => 'Test Boiler',
            'manufacturer_part_number' => 'MPN1',
        ]);
    }
}
