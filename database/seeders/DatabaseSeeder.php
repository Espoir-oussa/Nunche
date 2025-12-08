<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Langue;
use App\Enums\RoleEnum;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Crée le rôle 'lecteur' si inexistant
        $lecteurRole = Role::firstOrCreate([
                'nom_role' => RoleEnum::LECTEUR,
        ]);

        // Crée la langue par défaut si inexistant
        $defaultLangue = Langue::firstOrCreate([
            'code_langue' => 'fr',
        ], [
            'nom_langue' => 'Français',
            'description' => 'Langue française',
        ]);

        // Crée l'utilisateur de test avec les clés étrangères valides
        User::factory()->create([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'test@example.com',
            'role_id' => $lecteurRole->id,
            'langue_id' => $defaultLangue->id,
            'statut' => 'actif',
        ]);

        $this->call([
            LangueSeeder::class,
            AdminSeeder::class,
            RegionSeeder::class,
            TypeMediaSeeder::class,
            TypeContenuSeeder::class,
            // UserSeeder::class,
            // MediaSeeder::class,
            // CommentaireSeeder::class,
            // ContenuSeeder::class,
            // ParlerSeeder::class,
        ]);
    }
}
