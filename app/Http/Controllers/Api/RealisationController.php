<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Realisation;

class RealisationController extends Controller
{
    /** GET /api/realisations (public) */
    public function index()
    {
        $realisations = Realisation::orderBy('en_avant', 'desc')
            ->orderBy('ordre')
            ->get();

        return response()->json($realisations);
    }

    /** GET /api/realisations/{slug} (public) */
    public function showPublic(string $slug)
    {
        $realisation = Realisation::where('slug', $slug)->firstOrFail();

        return response()->json($realisation);
    }
}
