<?php

namespace App\Services;

use App\Models\FechaEspecial;
use App\Models\Trimestre;
use App\Repositories\ActividadRepository;
use Illuminate\Support\Facades\Cache;

class CalendarioService
{
    public function __construct(private ActividadRepository $actividadRepository)
    {
    }

    /**
     * Eventos combinados (actividades + fechas especiales) en formato FullCalendar.
     * Cacheado por trimestre (Fase 3, mejora 3): cambia poco, se consulta seguido.
     */
    public function obtenerEventos(Trimestre $trimestre): array
    {
        return Cache::remember(
            $this->claveCache($trimestre->id),
            now()->addMinutes(15),
            fn() => $this->construirEventos($trimestre)
        );
    }

    private function construirEventos(Trimestre $trimestre): array
    {
        $actividades = $this->actividadRepository->paraCalendario($trimestre);

        $eventosActividades = $actividades->map(function ($actividad) {
            $esPendiente = $actividad->estadoActual->nombre === 'Pendiente';

            return [
                'id' => 'actividad-' . $actividad->id,
                'title' => $actividad->nombre,
                'start' => $actividad->fecha->toDateString(),
                'color' => $actividad->organizacion->color,
                'className' => $esPendiente ? 'evt-pendiente' : 'evt-aprobada',
                'extendedProps' => [
                    'tipo' => 'actividad',
                    'actividadId' => $actividad->id,
                    'org' => $actividad->organizacion->nombre,
                    'orgKey' => $actividad->organizacion_id,
                ],
            ];
        });

        $eventosFechasEspeciales = FechaEspecial::query()
            ->whereBetween('fecha_inicio', [$trimestre->fecha_inicio, $trimestre->fecha_fin])
            ->get()
            ->map(fn($fecha) => [
                'id' => 'fecha-' . $fecha->id,
                'title' => $fecha->nombre,
                'start' => $fecha->fecha_inicio->toDateString(),
                // FullCalendar trata "end" como exclusivo, por eso +1 día para incluir el último día del rango.
                'end' => $fecha->fecha_fin->copy()->addDay()->toDateString(),
                'className' => 'evt-especial',
                'extendedProps' => ['tipo' => 'fecha_especial', 'orgKey' => null],
            ]);

        return $eventosActividades->concat($eventosFechasEspeciales)->values()->all();
    }

    /** Se llama desde los Listeners de ActividadEstadoCambiado / creación de FechaEspecial (Fase 3). */
    public static function invalidarCache(int $trimestreId): void
    {
        Cache::forget((new self(app(ActividadRepository::class)))->claveCache($trimestreId));
    }

    private function claveCache(int $trimestreId): string
    {
        return "calendario.trimestre.{$trimestreId}";
    }
}
