<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvCompetence;
use App\Models\CvExperience;
use App\Models\CvFormation;
use App\Models\CvPdf;

class CvController extends Controller
{
    /**
     * GET /api/cv (public)
     * Retourne tout ce qu'il faut pour construire la page CV en un seul appel.
     */
    public function index()
    {
        return response()->json([
            'experiences' => CvExperience::orderBy('date_debut', 'desc')->get(),
            'formations' => CvFormation::orderBy('date_debut', 'desc')->get(),
            'competences' => CvCompetence::orderBy('categorie')->orderBy('ordre')->get(),
            'pdf' => optional(CvPdf::where('actif', true)->latest()->first())->url,
        ]);
    }
}
