<?php

namespace App\Http\Controllers;

use App\Enums\RolUsuario;
use App\Http\Requests\Actividad\RechazarActividadRequest;
use App\Http\Requests\Actividad\StoreActividadRequest;
use App\Models\Actividad;
use App\Models\CategoriaPresupuesto;
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

    /** "Mis Actividades" (Presidencia) o "Actividades" (resto de roles con visibilidad global). */
    public function index(): View
    {
        $trimestre = Trimestre::obtenerActivo();
        $usuario = auth()->user();

        $actividades = $usuario->rolEnum() === RolUsuario::Presidencia
            ? $this->actividadRepository->porOrganizacion($usuario->organizacion_id, $trimestre)
            : $this->actividadRepository->pendientes($trimestre); // Consejo/Secretarías: cola de revisión

        return view('actividades.index', compact('actividades', 'trimestre'));
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
}
