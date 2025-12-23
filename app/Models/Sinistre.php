<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sinistre extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_sinistre',
        'vehicule_id',
        'chauffeur_id',
        'date_sinistre',
        'heure_sinistre',
        'lieu_sinistre',
        'type_sinistre',
        'description',
        'gravite',
        'responsable',
        'statut_sinistre',
        'montant_estime',
        'cree_par',
        'date_creation',
    ];

    protected $casts = [
        'date_sinistre' => 'date',
        'heure_sinistre' => 'string',
        'date_creation' => 'datetime',
        'montant_estime' => 'decimal:2',
    ];

    protected $appends = ['cout_total'];

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function chauffeur()
    {
        return $this->belongsTo(Chauffeur::class);
    }

    public function assurance()
    {
        return $this->hasOne(AssuranceSinistre::class);
    }

    public function reparations()
    {
        return $this->hasMany(ReparationSinistre::class);
    }

    public function getCoutTotalAttribute(): float
    {
        $base = (float) ($this->montant_estime ?? 0);
        if ($this->relationLoaded('reparations')) {
            return $base + (float) $this->reparations->sum('cout_reparation');
        }

        $reparationsSum = (float) $this->reparations()->sum('cout_reparation');
        return $base + $reparationsSum;
    }
}
