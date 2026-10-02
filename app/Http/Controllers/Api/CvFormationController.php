<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvFormation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CvFormationController extends Controller
{
    private function rules(): array
    {
        return [
            'diplome' => 'required|string|max:255',
            'etablissement' => 'required|string|max:255',
            'lieu' => 'nullable|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer',
        ];
    }

    public function index()
    {
        return response()->json(CvFormation::orderBy('date_debut', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $formation = CvFormation::create($validator->validated());
        return response()->json($formation, 201);
    }

    public function update(Request $request, CvFormation $cvFormation)
    {
        $rules = $this->rules();
        foreach ($rules as $key => $rule) {
            if (str_starts_with($rule, 'required')) {
                $rules[$key] = 'sometimes|' . $rule;
            }
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cvFormation->update($validator->validated());
        return response()->json($cvFormation);
    }

    public function destroy(CvFormation $cvFormation)
    {
        $cvFormation->delete();
        return response()->json(['message' => 'Formation supprimée']);
    }
}
