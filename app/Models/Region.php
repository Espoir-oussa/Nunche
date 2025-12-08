<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends Model
{
    use HasFactory;
    protected $fillable = ['nom_region', 'description', 'population', 'superficie', 'localisation'];

    public function contenus()
    {
        return $this->hasMany(Contenu::class);
    }

    public function langues()
    {
        return $this->belongsToMany(Langue::class, 'parler');
    }
}
