<?php

namespace Database\Seeders;

use App\Models\CategoriaPresupuesto;
use App\Models\Recurso;
use App\Models\TipoFechaEspecial;
use Illuminate\Database\Seeder;

class CatalogosSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Flyer', 'Equipo de sonido', 'Proyector', 'Cocina', 'Mesas', 'Sillas', 'Otro'] as $nombre) {
            Recurso::updateOrCreate(['nombre' => $nombre], ['estado' => 'activo']);
        }

        $tiposFecha = [
            ['nombre' => 'Conferencia General', 'color' => '#2B3A4A'],
            ['nombre' => 'Conferencia de Estaca', 'color' => '#3D5066'],
            ['nombre' => 'Feriado', 'color' => '#A64B3F'],
            ['nombre' => 'Fecha reservada', 'color' => '#C08A3E'],
            ['nombre' => 'Otra', 'color' => '#26282B'],
        ];
        foreach ($tiposFecha as $tipo) {
            TipoFechaEspecial::updateOrCreate(['nombre' => $tipo['nombre']], $tipo);
        }

        foreach (['Alimentación', 'Materiales', 'Transporte', 'Decoración', 'Otro'] as $nombre) {
            CategoriaPresupuesto::updateOrCreate(['nombre' => $nombre], ['estado' => 'activo']);
        }
    }
}
