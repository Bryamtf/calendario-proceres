<?php

namespace App\Http\Controllers;

use App\Enums\RolUsuario;
use App\Http\Requests\Actividad\RechazarActividadRequest;
use App\Http\Requests\Actividad\StoreActividadRequest;
use App\Http\Requests\Actividad\UpdateActividadRequest;
use App\Models\Actividad;
use App\Models\CategoriaPresupuesto;
use App\Models\Organizacion;
use App\Models\Recurso;
use App\Models\Trimestre;
use App\Repositories\ActividadRepository;
use App\Services\ActividadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActividadController extends Controller
{
    public function __construct(
        private ActividadService $actividadService,
        private ActividadRepository $actividadRepository,
    ) {
    }

    /** "Mis Actividades" (Presidencia, filtrable por estado) o "Actividades" (resto, filtrable por organización + estado). */
    public function index(Request $request): View
    {
        $trimestre = Trimestre::obtenerActivo();
        $usuario = auth()->user();
        $esPresidencia = $usuario->rolEnum() === RolUsuario::Presidencia;

        $filtros = [
            'trimestre_id' => $trimestre?->id,
            'organizacion_id' => $esPresidencia ? $usuario->organizacion_id : $request->query('organizacion_id'),
            'estados' => $request->query('estados', []),
        ];

        $actividades = $this->actividadRepository->filtradas($filtros);

        return view('actividades.index', [
            'actividades' => $actividades,
            'trimestre' => $trimestre,
            'filtros' => $filtros,
            'organizaciones' => $esPresidencia ? null : Organizacion::activas()->orderBy('nombre')->get(),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Actividad::class);

        return view('actividades.create', [
            'fechaPrecargada' => $request->query('fecha'),
            'categorias' => CategoriaPresupuesto::activas()->orderBy('nombre')->get(),
            'recursos' => Recurso::activos()->orderBy('nombre')->get(),
            'organizaciones' => $request->user()->organizacion_id
                ? null
                : \App\Models\Organizacion::activas()->orderBy('nombre')->get(),
        ]);
    }

    public function store(StoreActividadRequest $request): RedirectResponse
    {
        $actividad = $this->actividadService->crear($request->validated(), $request->user());

        return redirect()
            ->route('actividades.show', $actividad)
            ->with('exito', 'Tu propuesta fue enviada. Queda Pendiente hasta el próximo Consejo de Obispado.');
    }

    public function show(Actividad $actividad): View
    {
        $this->authorize('view', $actividad);

        $actividad->load(['organizacion', 'estadoActual', 'presupuestoItems.categoria', 'participantes', 'recursos', 'comentarios.usuario', 'historialEstados.usuario']);

        return view('actividades.show', compact('actividad'));
    }

    public function edit(Actividad $actividad): View
    {
        $this->authorize('update', $actividad);

        $actividad->load('participantes', 'presupuestoItems', 'recursos');

        return view('actividades.edit', [
            'actividad' => $actividad,
            'categorias' => CategoriaPresupuesto::activas()->orderBy('nombre')->get(),
            'recursos' => Recurso::activos()->orderBy('nombre')->get(),
            'organizaciones' => auth()->user()->organizacion_id
                ? null
                : Organizacion::activas()->orderBy('nombre')->get(),
        ]);
    }

    public function update(UpdateActividadRequest $request, Actividad $actividad): RedirectResponse
    {
        $this->actividadService->actualizar($actividad, $request->validated(), $request->user());

        return redirect()
            ->route('actividades.show', $actividad)
            ->with('exito', 'Actividad actualizada.');
    }

    public function aprobar(Actividad $actividad): RedirectResponse
    {
        $this->authorize('aprobar', $actividad);

        $this->actividadService->aprobar($actividad, auth()->user());

        return redirect()
            ->route('actividades.show', $actividad)
            ->with('exito', 'Actividad aprobada.');
    }

    public function rechazar(RechazarActividadRequest $request, Actividad $actividad): RedirectResponse
    {
        $this->actividadService->rechazar($actividad, $request->user(), $request->validated('motivo'));

        return redirect()
            ->route('actividades.show', $actividad)
            ->with('exito', 'Actividad rechazada.');
    }

    public function migrar(Actividad $actividad): RedirectResponse
    {
        $this->authorize('migrarANuevoTrimestre', $actividad);

        $nueva = $this->actividadService->migrarANuevoTrimestre($actividad, auth()->user());

        return redirect()
            ->route('actividades.show', $nueva)
            ->with('exito', 'Actividad migrada al trimestre activo.');
    }

    public function cancelar(Request $request, Actividad $actividad): RedirectResponse
    {
        $this->authorize('cancelar', $actividad);

        $datos = $request->validate(['motivo' => ['required', 'string', 'min:5']]);

        $this->actividadService->cancelar($actividad, $request->user(), $datos['motivo']);

        return redirect()
            ->route('actividades.show', $actividad)
            ->with('exito', 'Actividad cancelada.');
    }

    public function comentar(Request $request, Actividad $actividad): RedirectResponse
    {
        $this->authorize('comentar', $actividad);

        $request->validate(['comentario' => ['required', 'string']]);

        $actividad->comentarios()->create([
            'usuario_id' => auth()->id(),
            'comentario' => $request->input('comentario'),
        ]);

        return back()->with('exito', 'Comentario agregado.');
    }

    /** JSON resumido para el modal del Calendario — evita salir de esa pantalla para revisar/aprobar. */
    public function resumen(Actividad $actividad)
    {
        $this->authorize('view', $actividad);

        $actividad->load('organizacion', 'estadoActual');

        return response()->json([
            'id' => $actividad->id,
            'nombre' => $actividad->nombre,
            'organizacion' => $actividad->organizacion->nombre,
            'color' => $actividad->organizacion->color,
            'fecha' => $actividad->fecha->format('d M Y'),
            'horaInicio' => \Carbon\Carbon::parse($actividad->hora_inicio)->format('g:i a'),
            'horaFin' => \Carbon\Carbon::parse($actividad->hora_fin)->format('g:i a'),
            'lugar' => $actividad->lugar,
            'objetivo' => $actividad->objetivo,
            'estado' => $actividad->estadoActual->nombre,
            'estadoColor' => $actividad->estadoActual->color,
            'solicitaPresupuesto' => $actividad->solicita_presupuesto,
            'presupuestoTotal' => $actividad->solicita_presupuesto ? $actividad->montoTotalSolicitado() : null,
            'puedeAprobar' => \Illuminate\Support\Facades\Gate::allows('aprobar', $actividad),
            'urlDetalle' => route('actividades.show', $actividad),
            'urlAprobar' => route('actividades.aprobar', $actividad),
            'urlRechazar' => route('actividades.rechazar', $actividad),
        ]);
    }
}
