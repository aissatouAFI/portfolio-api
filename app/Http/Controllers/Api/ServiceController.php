<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /** GET /api/services (public) */
    public function index()
    {
        $services = Service::where('actif', true)
            ->orderBy('ordre')
            ->get();

        return response()->json($services);
    }

    /** GET /api/admin/services (admin, y compris inactifs) */
    public function adminIndex()
    {
        return response()->json(Service::orderBy('ordre')->get());
    }

    /** POST /api/admin/services */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'icone' => 'nullable|string|max:100',
            'ordre' => 'nullable|integer',
            'actif' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $service = Service::create($validator->validated());

        return response()->json($service, 201);
    }

    /** GET /api/admin/services/{id} */
    public function show(Service $service)
    {
        return response()->json($service);
    }

    /** PUT /api/admin/services/{id} */
    public function update(Request $request, Service $service)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'icone' => 'nullable|string|max:100',
            'ordre' => 'nullable|integer',
            'actif' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $service->update($validator->validated());

        return response()->json($service);
    }

    /** DELETE /api/admin/services/{id} */
    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json(['message' => 'Service supprimé']);
    }
}
