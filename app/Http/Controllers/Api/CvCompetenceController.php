<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvCompetence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CvCompetenceController extends Controller
{
    private function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'categorie' => 'required|in:backend,frontend,outils,autre',
            'niveau' => 'nullable|integer|min:0|max:100',
            'ordre' => 'nullable|integer',
        ];
    }

    public function index()
    {
        return response()->json(CvCompetence::orderBy('categorie')->orderBy('ordre')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $competence = CvCompetence::create($validator->validated());
        return response()->json($competence, 201);
    }

    public function update(Request $request, CvCompetence $cvCompetence)
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

        $cvCompetence->update($validator->validated());
        return response()->json($cvCompetence);
    }

    public function destroy(CvCompetence $cvCompetence)
    {
        $cvCompetence->delete();
        return response()->json(['message' => 'Compétence supprimée']);
    }
}
