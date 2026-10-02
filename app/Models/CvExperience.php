<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvExperience extends Model
{
    use HasFactory;

    protected $table = 'cv_experiences';

    protected $fillable = [
        'poste',
        'entreprise',
        'lieu',
        'date_debut',
        'date_fin',
        'description',
        'ordre',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];
}
