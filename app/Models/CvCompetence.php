<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvCompetence extends Model
{
    use HasFactory;

    protected $table = 'cv_competences';

    protected $fillable = [
        'nom',
        'categorie',
        'niveau',
        'ordre',
    ];
}
