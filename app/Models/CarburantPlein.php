<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarburantPlein extends Model
{
    use HasFactory;

    protected $table = 'carburant_pleins';

    protected $fillable = [
        'vehicule_id',
        'chauffeur_id',
        'date_plein',
        'kilometrage',
        'quantite',
        'prix_unitaire',
        'montant_total',
        'type_carburant',
        'station',
        'mode_paiement',
        'observation',
    ];

    protected $casts = [
        'date_plein'     => 'date',
        'kilometrage'    => 'integer',
        'quantite'       => 'decimal:2',
        'prix_unitaire'  => 'decimal:2',
        'montant_total'  => 'decimal:2',
    ];

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function chauffeur()
    {
        return $this->belongsTo(Chauffeur::class);
    }

    /**
     * Scope : filtrer par véhicule
     */
    public function scopeParVehicule($query, $vehiculeId)
    {
        return $query->where('vehicule_id', $vehiculeId);
    }

    /**
     * Scope : filtrer par type de carburant
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_carburant', $type);
    }

    /**
     * Scope : filtrer par mode de paiement
     */
    public function scopeParMode($query, $mode)
    {
        return $query->where('mode_paiement', $mode);
    }

    /**
     * Scope : filtrer par période
     */
    public function scopePeriode($query, $dateStart, $dateEnd)
    {
        if ($dateStart) $query->whereDate('date_plein', '>=', $dateStart);
        if ($dateEnd)   $query->whereDate('date_plein', '<=', $dateEnd);
        return $query;
    }
}
