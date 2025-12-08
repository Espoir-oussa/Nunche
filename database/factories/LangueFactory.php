<?php
namespace Database\Factories;

use App\Models\Langue;
use Illuminate\Database\Eloquent\Factories\Factory;

class LangueFactory extends Factory
{
    protected $model = Langue::class;

    public function definition(): array
    {
        $codesSeeder = ['fr', 'fon', 'adja', 'aiz', 'bba', 'bip', 'ddn', 'tbz', 'fod', 'gej', 'gun', 'hau', 'idd', 'ife', 'kaf', 'lok', 'mah', 'mkl', 'nag', 'ful', 'tbz2', 'tcb', 'wem', 'yor'];
        do {
            $code = $this->faker->unique()->languageCode();
        } while (in_array($code, $codesSeeder));
        return [
            'nom_langue' => $code,
            'code_langue' => $code,
            'description' => $this->faker->sentence(),
        ];
    }
}
