<?php

namespace Database\Seeders;

use App\Models\EstadoActividadModelo;
use Illuminate\Database\Seeder;

class EstadoActividadSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['nombre' => 'Pendiente', 'color' => '#C08A3E', 'orden' => 1],
            ['nombre' => 'Aprobada', 'color' => '#5B7A5D', 'orden' => 2],
            ['nombre' => 'Rechazada', 'color' => '#A64B3F', 'orden' => 3],
            ['nombre' => 'Realizada', 'color' => '#5B7A5D', 'orden' => 4],
            ['nombre' => 'Cancelada', 'color' => '#A64B3F', 'orden' => 5],
            ['nombre' => 'No Procesada', 'color' => '#26282B', 'orden' => 6],
        ];

        foreach ($estados as $estado) {
            EstadoActividadModelo::updateOrCreate(['nombre' => $estado['nombre']], $estado);
        }
    }
}
