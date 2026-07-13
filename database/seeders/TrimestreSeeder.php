<?php

namespace Database\Seeders;

use App\Models\Trimestre;
use Illuminate\Database\Seeder;

class TrimestreSeeder extends Seeder
{
    public function run(): void
    {
        Trimestre::firstOrCreate(
            ['estado' => 'activo'],
            [
                'nombre' => 'Q3 2026',
                'fecha_inicio' => '2026-07-01',
                'fecha_fin' => '2026-09-30',
                'estado' => 'activo',
            ]
        );
    }
}
