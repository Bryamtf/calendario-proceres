<?php

namespace App\Http\Controllers;

use App\Models\Organizacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrganizacionController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Organizacion::class);

        $organizaciones = Organizacion::orderBy('nombre')->get();

        return view('organizaciones.index', compact('organizaciones'));
    }

    public function create(): View
    {
        $this->authorize('create', Organizacion::class);

        return view('organizaciones.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Organizacion::class);

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100', Rule::unique('organizaciones', 'nombre')],
            'color' => ['required', 'string', 'max:7'],
        ]);
        $datos['estado'] = 'activo';

        Organizacion::create($datos);

        return redirect()->route('organizaciones.index')->with('exito', 'Organización creada.');
    }

    public function edit(Organizacion $organizacion): View
    {
        $this->authorize('update', $organizacion);

        return view('organizaciones.edit', ['organizacion' => $organizacion]);
    }

    public function update(Request $request, Organizacion $organizacion): RedirectResponse
    {
        $this->authorize('update', $organizacion);

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100', Rule::unique('organizaciones', 'nombre')->ignore($organizacion->id)],
            'color' => ['required', 'string', 'max:7'],
        ]);

        $organizacion->update($datos);

        return redirect()->route('organizaciones.index')->with('exito', 'Organización actualizada.');
    }

    /** Nunca se elimina (ya tiene actividades/presupuestos vinculados) — solo se desactiva. */
    public function toggleActivo(Organizacion $organizacion): RedirectResponse
    {
        $this->authorize('update', $organizacion);

        $organizacion->update(['estado' => $organizacion->estado === 'activo' ? 'inactivo' : 'activo']);

        return back()->with('exito', 'Estado actualizado.');
    }
}
