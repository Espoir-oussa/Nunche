<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parler;

class ParlerSeeder extends Seeder
{
    public function run(): void
    {
        Parler::factory()->count(10)->create();
    }
}
