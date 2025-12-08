<?php
namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegionFactory extends Factory
{
    protected $model = Region::class;

    public function definition(): array
    {
        return [
            'nom_region' => $this->faker->city(),
            'description' => $this->faker->sentence(),
            'population' => $this->faker->numberBetween(10000, 1000000),
            'superficie' => $this->faker->randomFloat(2, 100, 10000),
            'localisation' => $this->faker->country(),
        ];
    }
}
