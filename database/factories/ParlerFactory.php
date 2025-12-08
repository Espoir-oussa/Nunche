<?php
namespace Database\Factories;

use App\Models\Parler;
use App\Models\Region;
use App\Models\Langue;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParlerFactory extends Factory
{
    protected $model = Parler::class;

    public function definition(): array
    {
        return [
            'region_id' => Region::factory(),
            'langue_id' => Langue::factory(),
        ];
    }
}
