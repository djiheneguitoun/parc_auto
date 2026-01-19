<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterventionCategorie extends Model
{
    use HasFactory;

    protected $table = 'intervention_categories';

    protected $fillable = [
        'code',
        'libelle',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function operations()
    {
        return $this->hasMany(InterventionOperation::class, 'categorie_id');
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}
