<?php

namespace App\Http\Controllers;

use App\Models\CategoriaPresupuesto;
use App\Models\ConfiguracionSistema;
use App\Models\Recurso;
use App\Models\TipoFechaEspecial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ConfiguracionController extends Controller
{
    /** Mapa de "tipo" en la URL -> modelo Eloquent del catálogo correspondiente. */
    private const CATALOGOS = [
        'recursos' => Recurso::class,
        'fechas' => TipoFechaEspecial::class,
        'categorias' => CategoriaPresupuesto::class,
    ];

    public function edit(): View
    {
        Gate::authorize('gestionar-configuracion');

        return view('configuracion.edit', [
            'configuracion' => ConfiguracionSistema::obtener(),
            'recursos' => Recurso::orderBy('nombre')->get(),
            'tiposFecha' => TipoFechaEspecial::orderBy('nombre')->get(),
            'categorias' => CategoriaPresupuesto::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        Gate::authorize('gestionar-configuracion');

        $datos = $request->validate([
            'nombre_barrio' => ['required', 'string', 'max:150'],
            'dias_gracia_cierre_trimestre' => ['required', 'integer', 'min:0', 'max:30'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $configuracion = ConfiguracionSistema::obtener();

        if ($request->hasFile('logo')) {
            $datos['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $configuracion->update($datos);

        return redirect()->route('configuracion.edit')->with('exito', 'Configuración guardada.');
    }

    public function storeCatalogo(Request $request, string $tipo): RedirectResponse
    {
        Gate::authorize('gestionar-configuracion');
        $modelo = $this->resolverCatalogo($tipo);

        $datos = $request->validate(['nombre' => ['required', 'string', 'max:150']]);
        $datos['estado'] = 'activo';

        // TipoFechaEspecial no tiene columna 'estado' en su tabla (Fase 2) — se omite si no aplica.
        if ($tipo === 'fechas') {
            unset($datos['estado']);
        }

        $modelo::create($datos);

        return back()->with('exito', 'Elemento agregado.');
    }

    public function toggleCatalogoItem(string $tipo, int $id): RedirectResponse
    {
        Gate::authorize('gestionar-configuracion');

        if ($tipo === 'fechas') {
            // tipos_fecha_especial no maneja estado activo/inactivo en el modelo actual (Fase 2).
            return back()->with('error', 'Los tipos de fecha especial no se pueden desactivar por ahora.');
        }

        $modelo = $this->resolverCatalogo($tipo);
        $item = $modelo::findOrFail($id);
        $item->update(['estado' => $item->estado === 'activo' ? 'inactivo' : 'activo']);

        return back()->with('exito', 'Estado actualizado.');
    }

    private function resolverCatalogo(string $tipo): string
    {
        abort_unless(array_key_exists($tipo, self::CATALOGOS), 404);

        return self::CATALOGOS[$tipo];
    }
}
