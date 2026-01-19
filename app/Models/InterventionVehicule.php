<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'date_intervention' => 'date',
        'kilometrage' => 'integer',
        'cout' => 'decimal:2',
        'immobilisation_jours' => 'integer',
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
