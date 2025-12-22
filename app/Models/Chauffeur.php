<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chauffeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'date_naissance',
        'date_recrutement',
        'adresse',
        'telephone',
        'numero_permis',
        'date_permis',
        'lieu_permis',
        'statut',
        'mention',
        'comportement',
    ];

    public function vehicules()
    {
        return $this->hasMany(Vehicule::class);
    }
}
