<?php
namespace Database\Factories;

use App\Models\TypeContenu;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypeContenuFactory extends Factory
{
    protected $model = TypeContenu::class;

    public function definition()
    {
        return [
            'nom_contenu' => $this->faker->randomElement([
                'histoire',
                'recette',
                'article',
            ]),
        ];
    }
}
