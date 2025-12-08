<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parler extends Model
{
    use HasFactory;
    protected $table = 'parler';
    protected $fillable = ['region_id', 'langue_id'];
    public $incrementing = false;

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function langue()
    {
        return $this->belongsTo(Langue::class);
    }
}
