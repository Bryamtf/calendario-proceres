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
            ->whereHas('estadoActual', fn($q) => $q->whereIn('nombre', ['Pendiente', 'Aprobada']))
            ->orderBy('fecha')
            ->get();
    }

    /** Cola de aprobación del Consejo de Obispado (Fase 4 - Panel de revisión). */
    public function pendientes(Trimestre $trimestre): Collection
    {
        return Actividad::query()
            ->with(['organizacion', 'estadoActual'])
            ->where('trimestre_id', $trimestre->id)
            ->whereHas('estadoActual', fn($q) => $q->where('nombre', 'Pendiente'))
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
}
