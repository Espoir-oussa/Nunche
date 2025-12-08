<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeContenu;

class TypeContenuSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['histoire', 'recette', 'article'];
        foreach ($types as $type) {
            TypeContenu::firstOrCreate(['nom_contenu' => $type]);
        }
    }
}
