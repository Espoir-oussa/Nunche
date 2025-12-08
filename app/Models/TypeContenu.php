<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\TypeContenuEnum;
use App\Models\Contenu;

class TypeContenu extends Model
{
    use HasFactory;
    protected $fillable = ['nom_contenu'];

    protected $casts = [
        'nom_contenu' => TypeContenuEnum::class,
    ];

    public function contenus()
    {
        return $this->hasMany(Contenu::class);
    }
}
