<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InterventionVehicule extends Model
{
    use HasFactory;

    protected $table = 'intervention_vehicules';

    protected $fillable = [
        'vehicule_id',
        'operation_id',
        'date_intervention',
        'description',
        'kilometrage',
        'cout',
        'prestataire',
        'immobilisation_jours',
        'statut',
        'date_prochaine_km',
        'date_prochaine',
        'pieces_changees',
        'observations',
        'cree_par',
    ];

    protected $casts = [
        'date_intervention' => 'date',
        'date_prochaine' => 'date',
        'kilometrage' => 'integer',
        'cout' => 'decimal:2',
        'immobilisation_jours' => 'integer',
        'date_prochaine_km' => 'integer',
    ];

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function operation()
    {
        return $this->belongsTo(InterventionOperation::class, 'operation_id');
    }

    public function creePar()
    {
        return $this->belongsTo(Utilisateur::class, 'cree_par');
    }

    /**
     * Calcule automatiquement les prochaines échéances basées sur la périodicité
     */
    public function calculerProchainesEcheances(): void
    {
        $operation = $this->operation;
        
        if (!$operation || !$operation->isEntretien()) {
            return;
        }

        // Calcul du prochain kilométrage
        if ($operation->periodicite_km && $this->kilometrage) {
            $this->date_prochaine_km = $this->kilometrage + $operation->periodicite_km;
        }

        // Calcul de la prochaine date
        if ($operation->periodicite_mois && $this->date_intervention) {
            $this->date_prochaine = Carbon::parse($this->date_intervention)
                ->addMonths($operation->periodicite_mois);
        }

        $this->save();
    }

    /**
     * Met à jour ou crée le suivi pour cette intervention
     */
    public function mettreAJourSuivi(): void
    {
        $operation = $this->operation;
        
        if (!$operation || !$operation->isEntretien()) {
            return;
        }

        InterventionSuivi::updateOrCreate(
            [
                'vehicule_id' => $this->vehicule_id,
                'operation_id' => $this->operation_id,
            ],
            [
                'dernier_km' => $this->kilometrage,
                'derniere_date' => $this->date_intervention,
                'prochaine_echeance_km' => $this->date_prochaine_km,
                'prochaine_echeance_date' => $this->date_prochaine,
                'alerte_envoyee' => false,
            ]
        );
    }

    /**
     * Scope pour filtrer par type (entretien ou réparation)
     */
    public function scopeParType($query, string $typeCode)
    {
        return $query->whereHas('operation.type', fn($q) => $q->where('code', $typeCode));
    }

    /**
     * Scope pour les entretiens uniquement
     */
    public function scopeEntretiens($query)
    {
        return $this->scopeParType($query, 'ENT');
    }

    /**
     * Scope pour les réparations uniquement
     */
    public function scopeReparations($query)
    {
        return $this->scopeParType($query, 'REP');
    }
}
