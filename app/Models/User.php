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
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'photo', // Ta colonne dans la base
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

    /**
     * Accesseur pour profile_photo_path (attendu par Fortify)
     * Cette méthode permet d'utiliser $user->profile_photo_path
     * alors que la colonne s'appelle 'photo'
     */
    protected function profilePhotoPath(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->photo,
            set: fn ($value) => $this->attributes['photo'] = $value,
        );
    }

    /**
     * Accesseur pour l'URL de la photo de profil
     * Utilisé dans les vues via $user->profile_photo_url
     */
    public function getProfilePhotoUrlAttribute()
    {
        // Si pas de photo, retourner l'image par défaut
        if (!$this->photo) {
            return asset('images/default-avatar.png');
        }

        // Si c'est déjà une URL complète (Cloudinary), la retourner directement
        if (str_starts_with($this->photo, 'http')) {
            return $this->photo;
        }

        // Si c'est un chemin Cloudinary (ex: "profile-photos/filename.jpg")
        // Générer l'URL complète via Storage
        try {
            return Storage::disk('cloudinary')->url($this->photo);
        } catch (\Exception $e) {
            \Log::error('Erreur génération URL Cloudinary: ' . $e->getMessage());
            return asset('images/default-avatar.png');
        }
    }

    /**
     * Nom complet de l'utilisateur (utile pour les vues)
     */
    public function getNameAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

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
}
