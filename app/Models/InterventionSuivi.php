<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InterventionSuivi extends Model
{
    use HasFactory;

    protected $table = 'intervention_suivis';

    protected $fillable = [
        'vehicule_id',
        'operation_id',
        'dernier_km',
        'derniere_date',
        'prochaine_echeance_km',
        'prochaine_echeance_date',
        'alerte_envoyee',
    ];

    protected $casts = [
        'derniere_date' => 'date',
        'prochaine_echeance_date' => 'date',
        'dernier_km' => 'integer',
        'prochaine_echeance_km' => 'integer',
        'alerte_envoyee' => 'boolean',
    ];

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function operation()
    {
        return $this->belongsTo(InterventionOperation::class, 'operation_id');
    }

    /**
     * Vérifie si l'échéance est dépassée par date
     */
    public function isEcheanceDateDepassee(): bool
    {
        if (!$this->prochaine_echeance_date) {
            return false;
        }
        return Carbon::now()->greaterThan($this->prochaine_echeance_date);
    }

    /**
     * Vérifie si l'échéance est proche (dans les 30 jours)
     */
    public function isEcheanceDateProche(int $joursAvant = 30): bool
    {
        if (!$this->prochaine_echeance_date) {
            return false;
        }
        $dateLimit = Carbon::now()->addDays($joursAvant);
        return Carbon::parse($this->prochaine_echeance_date)->lessThanOrEqualTo($dateLimit) 
            && !$this->isEcheanceDateDepassee();
    }

    /**
     * Calcule les jours restants avant l'échéance
     */
    public function joursRestants(): ?int
    {
        if (!$this->prochaine_echeance_date) {
            return null;
        }
        return Carbon::now()->diffInDays($this->prochaine_echeance_date, false);
    }

    /**
     * Scope pour les échéances à venir dans les X jours
     */
    public function scopeEcheancesProches($query, int $jours = 30)
    {
        $dateLimit = Carbon::now()->addDays($jours);
        return $query->where('prochaine_echeance_date', '<=', $dateLimit)
                     ->where('prochaine_echeance_date', '>=', Carbon::now());
    }

    /**
     * Scope pour les échéances dépassées
     */
    public function scopeEcheancesDepassees($query)
    {
        return $query->where('prochaine_echeance_date', '<', Carbon::now());
    }

    /**
     * Scope pour les alertes non envoyées
     */
    public function scopeAlertesNonEnvoyees($query)
    {
        return $query->where('alerte_envoyee', false);
    }
}
