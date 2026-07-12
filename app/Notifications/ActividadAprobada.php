<?php

namespace App\Notifications;

use App\Models\Actividad;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActividadAprobada extends Notification
{
    use Queueable;

    public function __construct(public Actividad $actividad)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tu actividad fue aprobada')
            ->line("\"{$this->actividad->nombre}\" fue aprobada por el Consejo de Obispado.")
            ->line('Fecha: ' . $this->actividad->fecha->format('d/m/Y'))
            ->action('Ver actividad', route('actividades.show', $this->actividad));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'actividad_id' => $this->actividad->id,
            'nombre' => $this->actividad->nombre,
            'mensaje' => "Tu actividad \"{$this->actividad->nombre}\" fue aprobada.",
        ];
    }
}
