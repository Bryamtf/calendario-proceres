<?php

namespace App\Listeners;

use App\Enums\EstadoActividad;
use App\Enums\RolUsuario;
use App\Events\ActividadEstadoCambiado;
use App\Models\User;
use App\Notifications\ActividadAprobada;
use App\Notifications\ActividadPendienteAprobacion;
use App\Notifications\ActividadRechazada;

class NotificarCambioEstado
{
    public function handle(ActividadEstadoCambiado $event): void
    {
        match ($event->estadoNuevo) {
            EstadoActividad::Pendiente => $this->notificarConsejoObispado($event),
            EstadoActividad::Aprobada => $event->actividad->creador->notify(new ActividadAprobada($event->actividad)),
            EstadoActividad::Rechazada => $event->actividad->creador->notify(
                new ActividadRechazada($event->actividad, $event->comentario ?? 'Sin motivo especificado')
            ),
            default => null, // Cancelada, Realizada, No Procesada: sin notificación por ahora
        };
    }

    private function notificarConsejoObispado(ActividadEstadoCambiado $event): void
    {
        $consejoObispado = User::whereHas('role', function ($q) {
            $q->whereIn('nombre', [
                RolUsuario::Obispo->value,
                RolUsuario::PrimerConsejero->value,
                RolUsuario::SegundoConsejero->value,
            ]);
        })->where('activo', true)->get();

        foreach ($consejoObispado as $miembro) {
            $miembro->notify(new ActividadPendienteAprobacion($event->actividad));
        }
    }
}
