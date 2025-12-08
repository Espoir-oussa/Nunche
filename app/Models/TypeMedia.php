<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\TypeMediaEnum;
use App\Models\Media;

class TypeMedia extends Model
{
    use HasFactory;
    protected $fillable = ['nom_media'];

    protected $casts = [
        'nom_media' => TypeMediaEnum::class,
    ];

    public function media()
    {
        return $this->hasMany(Media::class);
    }
}
