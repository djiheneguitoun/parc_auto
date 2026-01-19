<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterventionOperation extends Model
{
    use HasFactory;

    protected $table = 'intervention_operations';

    protected $fillable = [
        'code',
        'libelle',
        'type_id',
        'categorie_id',
        'periodicite_km',
        'periodicite_mois',
        'cout_estime',
        'actif',
    ];

    protected $casts = [
        'periodicite_km' => 'integer',
        'periodicite_mois' => 'integer',
        'cout_estime' => 'decimal:2',
        'actif' => 'boolean',
    ];

    public function type()
    {
        return $this->belongsTo(InterventionType::class, 'type_id');
    }

    public function categorie()
    {
        return $this->belongsTo(InterventionCategorie::class, 'categorie_id');
    }

    public function interventions()
    {
        return $this->hasMany(InterventionVehicule::class, 'operation_id');
    }

    public function suivis()
    {
        return $this->hasMany(InterventionSuivi::class, 'operation_id');
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeEntretien($query)
    {
        return $query->whereHas('type', fn($q) => $q->where('code', 'ENT'));
    }

    public function scopeReparation($query)
    {
        return $query->whereHas('type', fn($q) => $q->where('code', 'REP'));
    }

    /**
     * Vérifie si l'opération est un entretien
     */
    public function isEntretien(): bool
    {
        return $this->type && $this->type->code === 'ENT';
    }

    /**
     * Vérifie si l'opération est une réparation
     */
    public function isReparation(): bool
    {
        return $this->type && $this->type->code === 'REP';
    }

    /**
     * Vérifie si l'opération a une périodicité définie
     */
    public function hasPeriodicity(): bool
    {
        return $this->periodicite_km !== null || $this->periodicite_mois !== null;
    }
}
