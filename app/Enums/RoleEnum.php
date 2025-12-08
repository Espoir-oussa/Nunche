<?php

namespace App\Enums;


// RoleEnum
enum RoleEnum: string {
    case ADMIN = 'admin';
    case MODERATEUR = 'moderateur';
    case LECTEUR = 'lecteur';
    case UTILISATEUR = 'utilisateur';
}