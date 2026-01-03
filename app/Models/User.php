<?php


namespace App\Models;

use App\Models\Role;
use App\Models\Langue;
use App\Enums\SexeEnum;
use App\Models\Contenu;
use App\Enums\UserStatus;
use App\Models\Commentaire;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
    if (!$this->profile_photo_path) {
        return asset('images/default-avatar.png');
    }

    // Si déjà une URL, retourner directement
    if (strpos($this->profile_photo_path, 'http') === 0) {
        return $this->profile_photo_path;
    }

    // Sinon, générer depuis Cloudinary
    try {
        return Storage::disk('cloudinary')->url($this->profile_photo_path);
    } catch (\Exception $e) {
        return asset('images/default-avatar.png');
    }
}
}
