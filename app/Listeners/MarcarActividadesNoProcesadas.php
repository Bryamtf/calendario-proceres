<?php

namespace App\Listeners;

use App\Events\TrimestreCerrado;
use App\Repositories\ActividadRepository;
use App\Services\ActividadService;

class MarcarActividadesNoProcesadas
{
    public function __construct(
        private ActividadService $actividadService,
        private ActividadRepository $actividadRepository,
    ) {
    }

    public function handle(TrimestreCerrado $event): void
    {
        $pendientes = $this->actividadRepository->pendientes($event->trimestre);

        foreach ($pendientes as $actividad) {
            $this->actividadService->marcarNoProcesada($actividad, $event->usuario);
        }
    }
}
