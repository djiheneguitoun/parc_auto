<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'code',
        'description',
        'marque',
        'modele',
        'annee',
        'couleur',
        'chassis',
        'chauffeur_id',
        'date_acquisition',
        'valeur',
        'statut',
        'date_creation',
        'categorie',
        'option_vehicule',
        'energie',
        'boite',
        'leasing',
        'utilisation',
        'affectation',
    ];

    protected $casts = [
        'date_creation' => 'datetime',
        'date_acquisition' => 'date',
        'statut' => 'boolean',
    ];

    public function chauffeur()
    {
        return $this->belongsTo(Chauffeur::class);
    }

    public function documents()
    {
        return $this->hasMany(VehiculeDocument::class);
    }

    public function images()
    {
        return $this->hasMany(VehiculeImage::class);
    }
}
