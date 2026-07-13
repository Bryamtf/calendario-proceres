<?php

namespace App\Listeners;

use App\Events\ActividadEstadoCambiado;
use App\Models\EstadoActividadModelo;
use App\Models\HistorialEstadoActividad;

class RegistrarHistorialEstado
{
    public function handle(ActividadEstadoCambiado $event): void
    {
        HistorialEstadoActividad::create([
            'actividad_id' => $event->actividad->id,
            'estado_anterior_id' => $event->estadoAnterior
                ? EstadoActividadModelo::where('nombre', $event->estadoAnterior->value)->value('id')
                : null,
            'estado_nuevo_id' => EstadoActividadModelo::where('nombre', $event->estadoNuevo->value)->value('id'),
            'usuario_id' => $event->usuario->id,
            'comentario' => $event->comentario,
            'fecha_cambio' => now(),
        ]);
    }
}
