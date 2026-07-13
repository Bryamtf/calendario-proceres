<?php

namespace App\Http\Controllers;

use App\Models\FechaEspecial;
use App\Models\Trimestre;
use App\Models\TipoFechaEspecial;
use App\Services\CalendarioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FechaEspecialController extends Controller
{
    public function index(): View
    {
        $fechas = FechaEspecial::with('tipo')->orderByDesc('fecha_inicio')->get();

        return view('fechas-especiales.index', compact('fechas'));
    }

    public function create(): View
    {
        $this->authorize('create', FechaEspecial::class);

        return view('fechas-especiales.create', [
            'tipos' => TipoFechaEspecial::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', FechaEspecial::class);

        $datos = $this->validar($request);
        $datos['creado_por'] = auth()->id();

        FechaEspecial::create($datos);
        $this->invalidarCacheCalendario();

        return redirect()->route('fechas-especiales.index')->with('exito', 'Fecha especial creada.');
    }

    public function edit(FechaEspecial $fechaEspecial): View
    {
        $this->authorize('update', $fechaEspecial);

        return view('fechas-especiales.edit', [
            'fechaEspecial' => $fechaEspecial,
            'tipos' => TipoFechaEspecial::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, FechaEspecial $fechaEspecial): RedirectResponse
    {
        $this->authorize('update', $fechaEspecial);

        $fechaEspecial->update($this->validar($request));
        $this->invalidarCacheCalendario();

        return redirect()->route('fechas-especiales.index')->with('exito', 'Fecha especial actualizada.');
    }

    public function destroy(FechaEspecial $fechaEspecial): RedirectResponse
    {
        $this->authorize('delete', $fechaEspecial);

        $fechaEspecial->delete();
        $this->invalidarCacheCalendario();

        return back()->with('exito', 'Fecha especial eliminada.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'tipo_fecha_especial_id' => ['required', 'exists:tipos_fecha_especial,id'],
            'nombre' => ['required', 'string', 'max:150'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'descripcion' => ['nullable', 'string'],
        ]);
    }

    private function invalidarCacheCalendario(): void
    {
        if ($trimestre = Trimestre::obtenerActivo()) {
            CalendarioService::invalidarCache($trimestre->id);
        }
    }
}
