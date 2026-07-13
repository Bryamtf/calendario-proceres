<?php

namespace App\Http\Controllers;

use App\Models\Organizacion;
use App\Models\Trimestre;
use App\Services\CalendarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CalendarioController extends Controller
{
    public function __construct(private CalendarioService $calendarioService)
    {
    }

    public function index(): View
    {
        $trimestre = Trimestre::obtenerActivo();
        $organizaciones = Organizacion::activas()->orderBy('nombre')->get();

        return view('calendario.index', compact('trimestre', 'organizaciones'));
    }

    public function eventos(): JsonResponse
    {
        $trimestre = Trimestre::obtenerActivo();

        if (!$trimestre) {
            return response()->json([]);
        }

        return response()->json($this->calendarioService->obtenerEventos($trimestre));
    }
}
