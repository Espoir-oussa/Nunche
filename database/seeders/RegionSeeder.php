<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['nom_region' => 'Alibori'],
            ['nom_region' => 'Atacora'],
            ['nom_region' => 'Atlantique'],
            ['nom_region' => 'Borgou'],
            ['nom_region' => 'Collines'],
            ['nom_region' => 'Couffo'],
            ['nom_region' => 'Donga'],
            ['nom_region' => 'Littoral'],
            ['nom_region' => 'Mono'],
            ['nom_region' => 'Ouémé'],
            ['nom_region' => 'Plateau'],
            ['nom_region' => 'Zou'],
        ];

        foreach ($regions as $region) {
            Region::firstOrCreate($region);
        }
    }
}
