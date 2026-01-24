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

        $admins = [
            [
                'nom' => 'Admin',
                'prenom' => 'Super',
                'email' => 'maurice.comlan@uac.bj',
                'password' => 'Eneam123',
                'sexe' => SexeEnum::M,
            ],
            [
                'nom' => 'OUSSA',
                'prenom' => 'Espoir',
                'email' => 'oussachadrac@gmail.com',
                'password' => '19D1002619**',
                'sexe' => SexeEnum::M,
            ],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'nom' => $admin['nom'],
                    'prenom' => $admin['prenom'],
                    'email' => $admin['email'],
                    'password' => Hash::make($admin['password']),
                    'sexe' => $admin['sexe'],
                    'statut' => UserStatus::ACTIF,
                    'role_id' => $role->id,
                    'langue_id' => 1,
                ]
            );
        }
    }
}
