<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Langue extends Model
{
    use HasFactory;
    protected $fillable = ['nom_langue', 'code_langue', 'description'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function regions()
    {
        return $this->belongsToMany(Region::class, 'parler');
    }
}
