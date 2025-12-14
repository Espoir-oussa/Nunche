<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nom_role' => RoleEnum::ADMIN->value],
            ['nom_role' => RoleEnum::MODERATEUR->value],
            ['nom_role' => RoleEnum::LECTEUR->value],
            ['nom_role' => RoleEnum::UTILISATEUR->value],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }

        $this->command->info('✅ Rôles créés avec succès : ' . implode(', ', array_column($roles, 'nom_role')));
    }
}
