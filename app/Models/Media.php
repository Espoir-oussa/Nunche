<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['chemin', 'description', 'contenu_id', 'type_media_id', 'type_contenu_id'];

    public function contenu()
    {
        return $this->belongsTo(Contenu::class);
    }

    public function typeMedia()
    {
        return $this->belongsTo(TypeMedia::class);
    }
}
