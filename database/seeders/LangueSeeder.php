<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Langue;

class LangueSeeder extends Seeder
{
    public function run(): void
    {
        $langues = [
            ['nom_langue' => 'Adja', 'code_langue' => 'adja', 'description' => 'Langue Adja'],
            ['nom_langue' => 'Aïzo', 'code_langue' => 'aiz', 'description' => 'Langue Aïzo / Ouatchi'],
            ['nom_langue' => 'Bariba', 'code_langue' => 'bba', 'description' => 'Langue Bariba'],
            ['nom_langue' => 'Biali', 'code_langue' => 'bip', 'description' => 'Langue Biali'],
            ['nom_langue' => 'Dendi', 'code_langue' => 'ddn', 'description' => 'Langue Dendi'],
            ['nom_langue' => 'Ditamari', 'code_langue' => 'tbz', 'description' => 'Langue Ditamari / Otamari'],
            ['nom_langue' => 'Fon', 'code_langue' => 'fon', 'description' => 'Langue Fon'],
            ['nom_langue' => 'Foodo', 'code_langue' => 'fod', 'description' => 'Langue Foodo'],
            ['nom_langue' => 'Gen', 'code_langue' => 'gej', 'description' => 'Langue Gen / Mina'],
            ['nom_langue' => 'Goun', 'code_langue' => 'gun', 'description' => 'Langue Goun'],
            ['nom_langue' => 'Hausa', 'code_langue' => 'hau', 'description' => 'Langue Hausa'],
            ['nom_langue' => 'Idaatcha', 'code_langue' => 'idd', 'description' => 'Langue Idaatcha'],
            ['nom_langue' => 'Ifè', 'code_langue' => 'ife', 'description' => 'Langue Ifè'],
            ['nom_langue' => 'Kotafon', 'code_langue' => 'kaf', 'description' => 'Langue Kotafon'],
            ['nom_langue' => 'Lokpa', 'code_langue' => 'lok', 'description' => 'Langue Lokpa'],
            ['nom_langue' => 'Mahi', 'code_langue' => 'mah', 'description' => 'Langue Mahi'],
            ['nom_langue' => 'Mokollé', 'code_langue' => 'mkl', 'description' => 'Langue Mokollé'],
            ['nom_langue' => 'Nagot', 'code_langue' => 'nag', 'description' => 'Langue Nagot'],
            ['nom_langue' => 'Peul', 'code_langue' => 'ful', 'description' => 'Langue Peul (Fulfulde)'],
            ['nom_langue' => 'Tammari', 'code_langue' => 'tbz2', 'description' => 'Langue Tammari'],
            ['nom_langue' => 'Tchabé', 'code_langue' => 'tcb', 'description' => 'Langue Tchabé'],
            ['nom_langue' => 'Wémè', 'code_langue' => 'wem', 'description' => 'Langue Wémè'],
            ['nom_langue' => 'Yoruba', 'code_langue' => 'yor', 'description' => 'Langue Yoruba'],
        ];

        foreach ($langues as $data) {
            Langue::updateOrCreate(
                ['code_langue' => $data['code_langue']],
                $data
            );
        }
    }
}
