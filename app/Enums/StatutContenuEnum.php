<?php

namespace App\Enums;

enum StatutContenuEnum: string {
    case BROUILLON = 'brouillon';
    case EN_ATTENTE = 'en_attente';
    case APPROUVE = 'approuve';
    case REJETE = 'rejete';
}
