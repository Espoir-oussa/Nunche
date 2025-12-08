<?php
namespace Database\Factories;

use App\Models\Commentaire;
use App\Models\User;
use App\Models\Contenu;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentaireFactory extends Factory
{
    protected $model = Commentaire::class;

    public function definition(): array
    {
        return [
            'texte' => $this->faker->paragraph(),
            'note' => $this->faker->numberBetween(1, 5),
            'date' => $this->faker->dateTime(),
            'user_id' => User::factory(),
            'contenu_id' => Contenu::factory(),
        ];
    }
}
