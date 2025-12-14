<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Enums\SexeEnum;
use App\Enums\UserStatus;
use App\Enums\RoleEnum;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate([
            'nom_role' => RoleEnum::ADMIN,
        ]);

        User::updateOrCreate([
            'email' => 'maurice.comlan@uac.bj',
        ], [
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'maurice.comlan@uac.bj',
            'password' => Hash::make('Eneam123'),
            'sexe' => SexeEnum::M,
            'statut' => UserStatus::ACTIF,
            'role_id' => $role->id,
            'langue_id' => 1, // à adapter selon tes langues
        ]);
    }
}
