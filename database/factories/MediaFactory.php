<?php
namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition(): array
    {
        return [
            'chemin' => $this->faker->imageUrl(),
            'description' => $this->faker->sentence(),
            'contenu_id' => \App\Models\Contenu::factory(),
            'type_media_id' => \App\Models\TypeMedia::factory(),
        ];
    }
}
