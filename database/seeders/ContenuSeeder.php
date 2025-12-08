<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contenu;

class ContenuSeeder extends Seeder
{
    public function run(): void
    {
        Contenu::factory()->count(10)->create();
    }
}
