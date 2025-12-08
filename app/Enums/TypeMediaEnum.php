<?php

namespace App\Enums;

enum TypeMediaEnum: string {
    case IMAGE = 'image';
    case AUDIO = 'audio';
    case VIDEO = 'video';
    case DOCUMENT = 'document';
}
