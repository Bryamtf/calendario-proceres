<?php

namespace App\Repositories;

use App\Models\Actividad;
use App\Models\Trimestre;
use Illuminate\Database\Eloquent\Collection;

class ActividadRepository
{
    /** Actividades Pendientes y Aprobadas de un trimestre, listas para pintar en FullCalendar. */
    public function paraCalendario(Trimestre $trimestre): Collection
    {
        return Actividad::query()
            ->with(['organizacion', 'estadoActual'])
            ->where('trimestre_id', $trimestre->id)
            ->whereHas('estadoActual', fn ($q) => $q->whereIn('nombre', ['Pendiente', 'Aprobada']))
            ->orderBy('fecha')
            ->get();
    }

    /** Cola de aprobación del Consejo de Obispado (Fase 4 - Panel de revisión). */
    public function pendientes(Trimestre $trimestre): Collection
    {
        return Actividad::query()
            ->with(['organizacion', 'estadoActual'])
            ->where('trimestre_id', $trimestre->id)
            ->whereHas('estadoActual', fn ($q) => $q->where('nombre', 'Pendiente'))
            ->orderBy('fecha')
            ->get();
    }

    /** Actividades de una organización específica dentro de un trimestre (dashboard de Presidencia). */
    public function porOrganizacion(int $organizacionId, Trimestre $trimestre): Collection
    {
        return Actividad::query()
            ->with('estadoActual')
            ->where('organizacion_id', $organizacionId)
            ->where('trimestre_id', $trimestre->id)
            ->orderBy('fecha')
            ->get();
    }

    /**
     * Listado filtrable de Actividades (Fase 9 mejorado): por trimestre, organización y estado(s).
     * @param array{trimestre_id?: ?int, organizacion_id?: ?int, estados?: array} $filtros
     */
    public function filtradas(array $filtros): Collection
    {
        $query = Actividad::query()->with(['organizacion', 'estadoActual']);

        if (! empty($filtros['trimestre_id'])) {
            $query->where('trimestre_id', $filtros['trimestre_id']);
        }

        if (! empty($filtros['organizacion_id'])) {
            $query->where('organizacion_id', $filtros['organizacion_id']);
        }

        if (! empty($filtros['estados'])) {
            $query->whereHas('estadoActual', fn ($q) => $q->whereIn('nombre', $filtros['estados']));
        }

        return $query->orderByDesc('fecha')->get();
    }
}
