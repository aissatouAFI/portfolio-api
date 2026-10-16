<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profil;

class ProfilController extends Controller
{
    /** GET /api/profil (public) */
    public function show()
    {
        $profil = Profil::firstOrCreate([]);

        return response()->json([
            'photo_url' => $profil->photo_url,
            'titre_accroche' => $profil->titre_accroche,
            'sous_titre' => $profil->sous_titre,
            'a_propos' => $profil->a_propos,
        ]);
    }
}
