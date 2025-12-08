<?php
namespace Database\Factories;

use App\Models\TypeMedia;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypeMediaFactory extends Factory
{
    protected $model = TypeMedia::class;

    public function definition(): array
    {
        return [
            'nom_media' => $this->faker->randomElement([
                'image',
                'audio',
                'video',
            ]),
        ];
    }
}
