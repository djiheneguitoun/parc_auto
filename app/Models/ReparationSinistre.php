<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReparationSinistre extends Model
{
    use HasFactory;

    protected $fillable = [
        'sinistre_id',
        'garage',
        'type_reparation',
        'date_debut',
        'date_fin_prevue',
        'date_fin_reelle',
        'cout_reparation',
        'prise_en_charge',
        'statut_reparation',
        'facture_reference',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin_prevue' => 'date',
        'date_fin_reelle' => 'date',
        'cout_reparation' => 'decimal:2',
    ];

    public function sinistre()
    {
        return $this->belongsTo(Sinistre::class);
    }
}
