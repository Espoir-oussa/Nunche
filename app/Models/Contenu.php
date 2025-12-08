<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\StatutContenuEnum;
use App\Models\Region;
use App\Models\Langue;
use App\Models\User;
use App\Models\TypeContenu;
use App\Models\Commentaire;
use App\Models\Media;

class Contenu extends Model
{
    use HasFactory;
    protected $fillable = [
        'titre', 'texte', 'date_creation', 'statut', 'date_validation',
        'parent_id', 'region_id', 'langue_id', 'moderateur_id', 'type_contenu_id', 'auteur_id'
    ];

    protected $casts = [
        'statut' => StatutContenuEnum::class,
        'date_creation' => 'datetime',
        'date_validation' => 'datetime',
    ];

    public function parent()
    {
        return $this->belongsTo(Contenu::class, 'parent_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function langue()
    {
        return $this->belongsTo(Langue::class);
    }

    public function moderateur()
    {
        return $this->belongsTo(User::class, 'moderateur_id');
    }

    public function typeContenu()
    {
        return $this->belongsTo(TypeContenu::class);
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }
}
