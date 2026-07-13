<?php

namespace App\Http\Controllers;

use App\Exports\ActividadesExport;
use App\Models\Organizacion;
use App\Models\Trimestre;
use App\Services\ReporteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReporteController extends Controller
{
    public function __construct(private ReporteService $reporteService)
    {
    }

    public function index(Request $request): View
    {
        Gate::authorize('ver-reportes');

        $filtros = $this->filtrosDesdeRequest($request);
        $actividades = $this->reporteService->generar($filtros);

        return view('reportes.index', [
            'actividades' => $actividades,
            'trimestres' => Trimestre::orderByDesc('fecha_inicio')->get(),
            'organizaciones' => Organizacion::activas()->orderBy('nombre')->get(),
            'filtros' => $filtros,
        ]);
    }

    public function excel(Request $request): BinaryFileResponse
    {
        Gate::authorize('ver-reportes');

        $actividades = $this->reporteService->generar($this->filtrosDesdeRequest($request));

        return Excel::download(new ActividadesExport($actividades), 'reporte-actividades.xlsx');
    }

    public function pdf(Request $request)
    {
        Gate::authorize('ver-reportes');

        $actividades = $this->reporteService->generar($this->filtrosDesdeRequest($request));

        return Pdf::loadView('reportes.pdf', compact('actividades'))->download('reporte-actividades.pdf');
    }

    private function filtrosDesdeRequest(Request $request): array
    {
        return [
            'trimestre_id' => $request->query('trimestre_id'),
            'organizacion_id' => $request->query('organizacion_id'),
            'estados' => $request->query('estados', ['Pendiente', 'Aprobada', 'Rechazada', 'Realizada', 'Cancelada', 'No Procesada']),
        ];
    }
}
