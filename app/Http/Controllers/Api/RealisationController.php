<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Realisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    /** GET /api/admin/realisations */
    public function adminIndex()
    {
        return response()->json(Realisation::orderBy('ordre')->get());
    }

    /** POST /api/admin/realisations (multipart/form-data pour l'image) */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:4096', // 4 Mo max
            'lien_demo' => 'nullable|url',
            'lien_github' => 'nullable|url',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string',
            'date_realisation' => 'nullable|date',
            'en_avant' => 'nullable|boolean',
            'ordre' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('realisations', 'public');
        }

        $realisation = Realisation::create($data);

        return response()->json($realisation, 201);
    }

    /** GET /api/admin/realisations/{id} */
    public function show(Realisation $realisation)
    {
        return response()->json($realisation);
    }

    /**
     * PUT /api/admin/realisations/{id}
     * Utiliser POST + _method=PUT en multipart si tu envoies une nouvelle image.
     */
    public function update(Request $request, Realisation $realisation)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'nullable|image|max:4096',
            'lien_demo' => 'nullable|url',
            'lien_github' => 'nullable|url',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string',
            'date_realisation' => 'nullable|date',
            'en_avant' => 'nullable|boolean',
            'ordre' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            if ($realisation->image) {
                Storage::disk('public')->delete($realisation->image);
            }
            $data['image'] = $request->file('image')->store('realisations', 'public');
        }

        $realisation->update($data);

        return response()->json($realisation);
    }

    /** DELETE /api/admin/realisations/{id} */
    public function destroy(Realisation $realisation)
    {
        if ($realisation->image) {
            Storage::disk('public')->delete($realisation->image);
        }
        $realisation->delete();

        return response()->json(['message' => 'Réalisation supprimée']);
    }
}
