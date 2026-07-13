<?php

namespace App\Http\Controllers;

use App\Enums\RolUsuario;
use App\Models\Actividad;
use App\Models\Organizacion;
use App\Models\PresupuestoOrganizacion;
use App\Models\Trimestre;
use App\Models\User;
use App\Repositories\ActividadRepository;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private ActividadRepository $actividadRepository)
    {
    }

    public function index(): View
    {
        $usuario = auth()->user();
        $trimestre = Trimestre::obtenerActivo();

        return match ($usuario->rolEnum()) {
            RolUsuario::Presidencia => $this->presidencia($usuario, $trimestre),
            RolUsuario::Administrador => $this->admin(),
            default => $this->obispado($trimestre), // Consejo de Obispado + Secretarías
        };
    }

    private function presidencia($usuario, ?Trimestre $trimestre): View
    {
        $actividades = $trimestre
            ? Actividad::where('organizacion_id', $usuario->organizacion_id)
                ->where('trimestre_id', $trimestre->id)
                ->with('estadoActual')
                ->orderByDesc('created_at')
                ->get()
            : collect();

        $proxima = $actividades
            ->filter(fn($a) => $a->estadoActual->nombre === 'Aprobada' && $a->fecha->isFuture())
            ->sortBy('fecha')
            ->first();

        $presupuesto = $trimestre
            ? PresupuestoOrganizacion::where('organizacion_id', $usuario->organizacion_id)
                ->where('trimestre_id', $trimestre->id)
                ->first()
            : null;

        return view('dashboard.presidencia', [
            'trimestre' => $trimestre,
            'pendientes' => $actividades->filter(fn($a) => $a->estadoActual->nombre === 'Pendiente')->count(),
            'aprobadas' => $actividades->filter(fn($a) => $a->estadoActual->nombre === 'Aprobada')->count(),
            'proxima' => $proxima,
            'recientes' => $actividades->take(5),
            'presupuesto' => $presupuesto,
        ]);
    }

    private function obispado(?Trimestre $trimestre): View
    {
        $actividadesTrimestre = $trimestre
            ? Actividad::where('trimestre_id', $trimestre->id)->with(['estadoActual', 'organizacion'])->get()
            : collect();

        $totalPresupuesto = $trimestre
            ? Actividad::where('trimestre_id', $trimestre->id)
                ->join('actividad_presupuesto_items', 'actividades.id', '=', 'actividad_presupuesto_items.actividad_id')
                ->sum('actividad_presupuesto_items.monto')
            : 0;

        $proximas = $actividadesTrimestre
            ->filter(fn($a) => $a->estadoActual->nombre === 'Aprobada' && $a->fecha->isFuture())
            ->sortBy('fecha')
            ->take(4);

        $organizaciones = Organizacion::activas()
            ->orderBy('nombre')
            ->with(['presupuestos' => fn($q) => $trimestre ? $q->where('trimestre_id', $trimestre->id) : $q->whereRaw('1=0')])
            ->get();

        return view('dashboard.obispado', [
            'trimestre' => $trimestre,
            'total' => $actividadesTrimestre->count(),
            'pendientesCount' => $actividadesTrimestre->filter(fn($a) => $a->estadoActual->nombre === 'Pendiente')->count(),
            'aprobadasCount' => $actividadesTrimestre->filter(fn($a) => $a->estadoActual->nombre === 'Aprobada')->count(),
            'totalPresupuesto' => $totalPresupuesto,
            'colaAprobacion' => $trimestre ? $this->actividadRepository->pendientes($trimestre)->take(5) : collect(),
            'proximas' => $proximas,
            'organizaciones' => $organizaciones,
        ]);
    }

    private function admin(): View
    {
        return view('dashboard.admin', [
            'usuariosActivos' => User::where('activo', true)->count(),
            'organizacionesCount' => Organizacion::activas()->count(),
            'trimestre' => Trimestre::obtenerActivo(),
            'actividadesTotal' => Actividad::count(),
        ]);
    }
}
