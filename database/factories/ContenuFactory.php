<?php
namespace Database\Factories;

use App\Models\Contenu;
use App\Models\Region;
use App\Models\Langue;
use App\Models\User;
use App\Models\TypeContenu;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContenuFactory extends Factory
{
    protected $model = Contenu::class;

    public function definition(): array
    {
        return [
            'titre' => $this->faker->sentence(),
            'texte' => $this->faker->paragraph(),
            'date_creation' => $this->faker->dateTime(),
            'statut' => 'brouillon',
            'date_validation' => null,
            'parent_id' => null,
            'region_id' => Region::factory(),
            'langue_id' => Langue::factory(),
            'moderateur_id' => User::factory(),
            'type_contenu_id' => TypeContenu::factory(),
            'auteur_id' => User::factory(),
        ];
    }
}
