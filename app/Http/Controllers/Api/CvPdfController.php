<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CvPdfController extends Controller
{
    /** GET /api/admin/cv-pdf - liste l'historique des CV uploadés */
    public function index()
    {
        return response()->json(CvPdf::latest()->get());
    }

    /**
     * POST /api/admin/cv-pdf
     * Upload un nouveau PDF et le rend actif (désactive les précédents).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fichier' => 'required|file|mimes:pdf|max:5120', // 5 Mo max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('fichier');
        $path = $file->store('cv', 'public');

        // Un seul CV actif à la fois
        CvPdf::where('actif', true)->update(['actif' => false]);

        $cvPdf = CvPdf::create([
            'fichier' => $path,
            'nom_original' => $file->getClientOriginalName(),
            'actif' => true,
        ]);

        return response()->json($cvPdf, 201);
    }

    /** DELETE /api/admin/cv-pdf/{id} */
    public function destroy(CvPdf $cvPdf)
    {
        Storage::disk('public')->delete($cvPdf->fichier);
        $cvPdf->delete();

        return response()->json(['message' => 'CV supprimé']);
    }
}
