<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Boiler;
use App\Models\Manufacturer;
use App\Models\FuelType;
use Illuminate\Support\Facades\{Http, Log};

class BoilerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $url = 'https://api.glowgreenltd.com/api/2025-test/boilers';
        $token = env('GLOW_GREEN_API_TOKEN');

        $response = Http::withHeaders([
            'X-GG-API-Key' => $token
        ])->get($url);

        if (!$response->successful()) {
            Log::error('Boiler API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            $this->command->error("Failed to fetch boilers from API. Status: " . $response->status());
            return;
        }

        $boilers = $response->json('data');

        if (!$boilers || !is_array($boilers)) {
            $this->command->error('Invalid or empty response data.');
            Log::error('Invalid boiler API structure', ['body' => $response->body()]);
            return;
        }

        foreach ($boilers as $item) {

            try {
                $manufacturerData = $item['boiler_manufacturer'];
                $manufacturer = Manufacturer::updateOrCreate(
                    ['id' => $manufacturerData['id']],
                    [
                        'name' => $manufacturerData['name'],
                        'location_id' => $manufacturerData['location_id'],
                        'created_at' => $manufacturerData['created_at'],
                        'updated_at' => $manufacturerData['updated_at'],
                    ]
                );

                $fuelTypeData = $item['fuel_type'];
                $fuelType = FuelType::updateOrCreate(
                    ['id' => $fuelTypeData['id']],
                    [
                        'fuel_type_ref' => $fuelTypeData['fuel_type_ref'],
                        'name' => $fuelTypeData['name'],
                    ]
                );

                Boiler::updateOrCreate(
                    ['id' => $item['id']],
                    [
                        'sku' => $item['sku'],
                        'boiler_manufacturer_id' => $manufacturer->id,
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'manufacturer_part_number' => $item['manufacturer_part_number'],
                        'fuel_type_id' => $fuelType->id,
                        'url' => $item['url'],
                    ]
                );
            } catch (Exception $e) {
                Log::error('Error saving boiler data', [
                    'boiler_id' => $item['id'],
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $this->command->info("Boilers seeded successfully.");
    }
}
