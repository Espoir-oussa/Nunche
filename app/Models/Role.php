<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\RoleEnum;
use App\Models\User;

class Role extends Model
{
    protected $fillable = ['nom_role'];

    protected $casts = [
        'nom_role' => RoleEnum::class,
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
