<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeMedia;

class TypeMediaSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['image', 'audio', 'video', 'document'];
        foreach ($types as $type) {
            TypeMedia::firstOrCreate(['nom_media' => $type]);
        }
    }
}
