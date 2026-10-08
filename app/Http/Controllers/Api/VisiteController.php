<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class VisiteController extends Controller
{
    /**
     * POST /api/visites (public)
     * Ajoute une visite au compteur du jour. Le site l'appelle une fois par session de navigation.
     */
    public function store()
    {
        $maintenant = now();

        // Insertion ou incrément en une seule requête (pas de doublon si deux visites arrivent en même temps)
        DB::table('visites')->upsert(
            [['date' => $maintenant->toDateString(), 'total' => 1, 'created_at' => $maintenant, 'updated_at' => $maintenant]],
            ['date'],
            ['total' => DB::raw('total + 1'), 'updated_at' => $maintenant]
        );

        return response()->json(['ok' => true], 201);
    }
}
