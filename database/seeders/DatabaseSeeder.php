<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Langue;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tables de référence ESSENTIELLES
        $this->call([
            LangueSeeder::class,      // Pour avoir la langue ID 1
            RoleSeeder::class,        // Pour créer tous les rôles (dont ADMIN)
        ]);

        // 2. VOTRE AdminSeeder (peut s'exécuter seul)
        $this->call([
            AdminSeeder::class,       // Crée le super admin
        ]);

        // 3. Optionnel : autres tables de référence
        $this->call([
            RegionSeeder::class,
            TypeMediaSeeder::class,
            TypeContenuSeeder::class,
        ]);

        // 4. Optionnel : utilisateur de test (non-admin)
        $lecteurRole = Role::where('nom_role', RoleEnum::LECTEUR)->first();
        $defaultLangue = Langue::where('code_langue', 'fr')->first();

        if ($lecteurRole && $defaultLangue) {
            User::factory()->create([
                'nom' => 'Test',
                'prenom' => 'User',
                'email' => 'test@example.com',
                'password' => Hash::make('password123'), // N'oubliez pas Hash
                'role_id' => $lecteurRole->id,
                'langue_id' => $defaultLangue->id,
                'statut' => 'actif',
            ]);
        }
    }
}
