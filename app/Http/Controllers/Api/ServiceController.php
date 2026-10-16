<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;

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
}
