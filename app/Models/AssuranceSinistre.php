<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssuranceSinistre extends Model
{
    use HasFactory;

    protected $fillable = [
        'sinistre_id',
        'compagnie_assurance',
        'numero_dossier',
        'date_declaration',
        'expert_nom',
        'date_expertise',
        'decision',
        'montant_pris_en_charge',
        'franchise',
        'date_validation',
        'statut_assurance',
    ];

    protected $casts = [
        'date_declaration' => 'date',
        'date_expertise' => 'date',
        'date_validation' => 'date',
        'montant_pris_en_charge' => 'decimal:2',
        'franchise' => 'decimal:2',
    ];

    public function sinistre()
    {
        return $this->belongsTo(Sinistre::class);
    }
}
