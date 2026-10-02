<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvFormation extends Model
{
    use HasFactory;

    protected $table = 'cv_formations';

    protected $fillable = [
        'diplome',
        'etablissement',
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
