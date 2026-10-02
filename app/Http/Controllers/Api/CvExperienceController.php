<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CvExperienceController extends Controller
{
    private function rules(): array
    {
        return [
            'poste' => 'required|string|max:255',
            'entreprise' => 'required|string|max:255',
            'lieu' => 'nullable|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer',
        ];
    }

    public function index()
    {
        return response()->json(CvExperience::orderBy('date_debut', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $experience = CvExperience::create($validator->validated());
        return response()->json($experience, 201);
    }

    public function update(Request $request, CvExperience $cvExperience)
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

        $cvExperience->update($validator->validated());
        return response()->json($cvExperience);
    }

    public function destroy(CvExperience $cvExperience)
    {
        $cvExperience->delete();
        return response()->json(['message' => 'Expérience supprimée']);
    }
}
