<?php

namespace Database\Factories;

use App\Models\Boiler;
use App\Models\FuelType;
use App\Models\Manufacturer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Boiler>
 */
class BoilerFactory extends Factory
{
    protected $model = Boiler::class;

    public function definition(): array
    {
        return [
            'sku' => $this->faker->unique()->uuid,
            'boiler_manufacturer_id' => Manufacturer::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
            'manufacturer_part_number' => strtoupper($this->faker->bothify('####??')),
            'fuel_type_id' => FuelType::factory(),
            'url' => $this->faker->url,
        ];
    }
}
