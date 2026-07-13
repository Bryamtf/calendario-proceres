<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ActividadesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private Collection $actividades)
    {
    }

    public function collection(): Collection
    {
        return $this->actividades;
    }

    public function headings(): array
    {
        return ['Actividad', 'Organización', 'Fecha', 'Estado', 'Asistencia esperada', 'Presupuesto solicitado'];
    }

    public function map($actividad): array
    {
        return [
            $actividad->nombre,
            $actividad->organizacion->nombre,
            $actividad->fecha->format('d/m/Y'),
            $actividad->estadoActual->nombre,
            $actividad->asistencia_esperada ?? 0,
            (float) $actividad->montoTotalSolicitado(),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
