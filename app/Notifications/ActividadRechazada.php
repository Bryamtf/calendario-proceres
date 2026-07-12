<?php

namespace App\Notifications;

use App\Models\Actividad;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActividadRechazada extends Notification
{
    use Queueable;

    public function __construct(public Actividad $actividad, public string $motivo)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tu actividad fue rechazada')
            ->line("\"{$this->actividad->nombre}\" fue rechazada por el Consejo de Obispado.")
            ->line('Motivo: ' . $this->motivo)
            ->action('Ver actividad', route('actividades.show', $this->actividad));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'actividad_id' => $this->actividad->id,
            'nombre' => $this->actividad->nombre,
            'motivo' => $this->motivo,
            'mensaje' => "Tu actividad \"{$this->actividad->nombre}\" fue rechazada.",
        ];
    }
}
