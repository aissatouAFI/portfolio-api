<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvPdf extends Model
{
    use HasFactory;

    protected $table = 'cv_pdfs';

    protected $fillable = [
        'fichier',
        'nom_original',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->fichier);
    }
}
