<?php

namespace App\Http\Controllers;

use App\Models\Trimestre;
use App\Services\TrimestreService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrimestreController extends Controller
{
    public function __construct(private TrimestreService $trimestreService)
    {
    }

    public function index(): View
    {
        $this->authorize('viewAny', Trimestre::class);

        $trimestres = Trimestre::orderByDesc('fecha_inicio')->get();

        return view('trimestres.index', compact('trimestres'));
    }

    public function create(): View
    {
        $this->authorize('create', Trimestre::class);

        return view('trimestres.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Trimestre::class);

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:50'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after:fecha_inicio'],
        ]);

        $this->trimestreService->abrir($datos);

        return redirect()->route('trimestres.index')->with('exito', 'Trimestre abierto.');
    }

    public function cerrar(Trimestre $trimestre): RedirectResponse
    {
        $this->authorize('cerrar', $trimestre);

        $this->trimestreService->cerrar($trimestre, auth()->user());

        return redirect()->route('trimestres.index')->with('exito', 'Trimestre cerrado. Las actividades Pendientes pasaron a "No Procesada".');
    }
}
