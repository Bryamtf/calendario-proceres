<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\ConfiguracionSistema;
use Illuminate\View\View;

class PublicoController extends Controller
{
    public function index(string $token): View
    {
        $configuracion = ConfiguracionSistema::obtener();

        // hash_equals evita timing attacks al comparar el token, aunque el riesgo real
        // aquí es bajo — es una llave de repuesto, no autenticación crítica.
        abort_unless(
            $configuracion->link_publico_token && hash_equals($configuracion->link_publico_token, $token),
            404
        );

        $actividades = Actividad::query()
            ->whereHas('estadoActual', fn($q) => $q->where('nombre', 'Aprobada'))
            ->whereDate('fecha', '>=', now()->toDateString())
            ->with('organizacion')
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get();

        return view('publico.index', compact('actividades', 'configuracion'));
    }
}
