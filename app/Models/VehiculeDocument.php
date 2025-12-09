<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'type',
        'numero',
        'libele',
        'partenaire',
        'debut',
        'expiration',
        'valeur',
        'num_facture',
        'date_facture',
        'vidange',
        'kilometrage',
        'piece',
        'reparateur',
        'type_reparation',
        'date_reparation',
        'typecarburant',
        'utilisation',
    ];

    protected $casts = [
        'debut' => 'date',
        'expiration' => 'date',
        'date_facture' => 'date',
        'date_reparation' => 'date',
    ];

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }
}
