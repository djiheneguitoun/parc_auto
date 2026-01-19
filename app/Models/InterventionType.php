<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterventionType extends Model
{
    use HasFactory;

    protected $table = 'intervention_types';

    protected $fillable = [
        'code',
        'libelle',
    ];

    public function operations()
    {
        return $this->hasMany(InterventionOperation::class, 'type_id');
    }
}
