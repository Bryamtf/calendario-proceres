<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificacionController extends Controller
{
    public function index(): View
    {
        $notificaciones = auth()->user()->notifications()->paginate(20);

        return view('notificaciones.index', compact('notificaciones'));
    }

    /** Marca como leída y redirige a la actividad relacionada (si aplica). */
    public function leer(string $id): RedirectResponse
    {
        $notificacion = auth()->user()->notifications()->findOrFail($id);
        $notificacion->markAsRead();

        $actividadId = $notificacion->data['actividad_id'] ?? null;

        return $actividadId
            ? redirect()->route('actividades.show', $actividadId)
            : redirect()->route('notificaciones.index');
    }

    public function marcarTodas(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    }
}
