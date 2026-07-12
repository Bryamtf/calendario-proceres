<?php

namespace App\Http\Controllers;

use App\Enums\RolUsuario;
use App\Models\Actividad;
use App\Models\Organizacion;
use App\Models\PresupuestoOrganizacion;
use App\Models\Trimestre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PresupuestoController extends Controller
{
    public function index(): View
    {
        $usuario = auth()->user();
        $trimestre = Trimestre::obtenerActivo();

        if ($usuario->rolEnum() === RolUsuario::Presidencia) {
            $presupuesto = $trimestre
                ? PresupuestoOrganizacion::where('organizacion_id', $usuario->organizacion_id)
                    ->where('trimestre_id', $trimestre->id)
                    ->first()
                : null;

            $actividades = $trimestre
                ? Actividad::where('organizacion_id', $usuario->organizacion_id)
                    ->where('trimestre_id', $trimestre->id)
                    ->where('solicita_presupuesto', true)
                    ->with('estadoActual')
                    ->orderBy('fecha')
                    ->get()
                : collect();

            return view('presupuesto.mio', compact('presupuesto', 'actividades', 'trimestre'));
        }

        // Resto de roles con visibilidad global (Fase 1/4): Admin, Consejo de Obispado,
        // Secretario de Barrio, Secretario Ejecutivo, Secretario de Finanzas.
        $organizaciones = Organizacion::activas()
            ->orderBy('nombre')
            ->with(['presupuestos' => fn($q) => $trimestre ? $q->where('trimestre_id', $trimestre->id) : $q->whereRaw('1=0')])
            ->get();

        return view('presupuesto.todas', compact('organizaciones', 'trimestre'));
    }

    public function update(Request $request, Organizacion $organizacion): RedirectResponse
    {
        $this->authorize('create', PresupuestoOrganizacion::class);

        $trimestre = Trimestre::obtenerActivo();
        abort_if(!$trimestre, 422, 'No hay trimestre activo para asignar presupuesto.');

        $datos = $request->validate([
            'monto_asignado' => ['required', 'numeric', 'min:0'],
        ]);

        PresupuestoOrganizacion::updateOrCreate(
            ['organizacion_id' => $organizacion->id, 'trimestre_id' => $trimestre->id],
            ['monto_asignado' => $datos['monto_asignado'], 'creado_por' => auth()->id()]
        );

        return back()->with('exito', 'Presupuesto asignado a ' . $organizacion->nombre . '.');
    }
}
