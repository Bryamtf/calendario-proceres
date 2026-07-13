<?php

namespace Database\Seeders;

use App\Models\Organizacion;
use Illuminate\Database\Seeder;

class OrganizacionSeeder extends Seeder
{
    public function run(): void
    {
        $organizaciones = [
            ['nombre' => 'Sociedad de Socorro', 'color' => '#fb7185'],
            ['nombre' => 'Cuórum de Élderes', 'color' => '#34d399'],
            ['nombre' => 'Primaria', 'color' => '#fbbf24'],
            ['nombre' => 'Mujeres Jóvenes', 'color' => '#a78bfa'],
            ['nombre' => 'Hombres Jóvenes', 'color' => '#38bdf8'],
            ['nombre' => 'Escuela Dominical', 'color' => '#fb923c'],
        ];

        foreach ($organizaciones as $org) {
            Organizacion::updateOrCreate(['nombre' => $org['nombre']], $org + ['estado' => 'activo']);
        }
    }
}
