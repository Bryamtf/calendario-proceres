<?php

namespace App\Notifications;

use App\Models\Actividad;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActividadPendienteAprobacion extends Notification
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
            ->subject('Pendiente de revisión: ' . $this->actividad->nombre)
            ->view('emails.actividad-pendiente', ['actividad' => $this->actividad]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'actividad_id' => $this->actividad->id,
            'nombre' => $this->actividad->nombre,
            'organizacion' => $this->actividad->organizacion->nombre,
            'mensaje' => "{$this->actividad->organizacion->nombre} propuso una nueva actividad: {$this->actividad->nombre}",
        ];
    }
}
