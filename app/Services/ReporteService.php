<?php

namespace App\Services;

use App\Models\Actividad;
use Illuminate\Database\Eloquent\Collection;

class ReporteService
{
    /**
     * @param array{trimestre_id?: ?int, organizacion_id?: ?int, estados?: array} $filtros
     */
    public function generar(array $filtros): Collection
    {
        $query = Actividad::query()
            ->with(['organizacion', 'estadoActual', 'presupuestoItems']);

        if (! empty($filtros['trimestre_id'])) {
            $query->where('trimestre_id', $filtros['trimestre_id']);
        }

        if (! empty($filtros['organizacion_id'])) {
            $query->where('organizacion_id', $filtros['organizacion_id']);
        }

        if (! empty($filtros['estados'])) {
            $query->whereHas('estadoActual', fn ($q) => $q->whereIn('nombre', $filtros['estados']));
        }

        return $query->orderBy('fecha')->get();
    }
}
