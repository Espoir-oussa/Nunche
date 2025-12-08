<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use App\Models\Role;
use App\Models\Langue;
use App\Models\Commentaire;
use App\Models\Contenu;
use App\Enums\SexeEnum;
use App\Enums\UserStatus;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'sexe',
        'date_naissance',
        'date_inscription',
        'statut',
        'photo',
        'role_id',
        'langue_id',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_naissance' => 'date',
        'date_inscription' => 'date',
        'sexe' => SexeEnum::class,
        'statut' => UserStatus::class,
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function langue()
    {
        return $this->belongsTo(Langue::class);
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    public function contenus()
    {
        return $this->hasMany(Contenu::class, 'auteur_id');
    }

    public function moderations()
    {
        return $this->hasMany(Contenu::class, 'moderateur_id');
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }
        return '/images/default-avatar.png';
    }
}
