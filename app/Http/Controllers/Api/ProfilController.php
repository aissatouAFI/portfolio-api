<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{
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

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'nullable|image|max:4096',
            'titre_accroche' => 'nullable|string|max:255',
            'sous_titre' => 'nullable|string|max:1000',
            'a_propos' => 'nullable|string|max:3000',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $profil = Profil::firstOrCreate([]);

        if ($request->hasFile('photo')) {
            if ($profil->photo) {
                Storage::disk('public')->delete($profil->photo);
            }
            $profil->photo = $request->file('photo')->store('profil', 'public');
        }

        $profil->titre_accroche = $request->input('titre_accroche', $profil->titre_accroche);
        $profil->sous_titre = $request->input('sous_titre', $profil->sous_titre);
        $profil->a_propos = $request->input('a_propos', $profil->a_propos);
        $profil->save();

        return response()->json([
            'photo_url' => $profil->photo_url,
            'titre_accroche' => $profil->titre_accroche,
            'sous_titre' => $profil->sous_titre,
            'a_propos' => $profil->a_propos,
        ]);
    }
}
