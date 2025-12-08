<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    protected $table = 'paiements';

    protected $fillable = [
        'user_id',
        'contenu_id', // nullable
        'montant',
        'statut',
        'numero',
        'paiement_methode',
        'transaction_id'
    ];

    public function contenu(): BelongsTo
    {
        return $this->belongsTo(Contenu::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
