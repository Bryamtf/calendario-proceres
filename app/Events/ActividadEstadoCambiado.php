<?php

namespace App\Events;

use App\Enums\EstadoActividad;
use App\Models\Actividad;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActividadEstadoCambiado
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Actividad $actividad,
        public ?EstadoActividad $estadoAnterior,
        public EstadoActividad $estadoNuevo,
        public User $usuario,
        public ?string $comentario = null,
    ) {
    }
}
