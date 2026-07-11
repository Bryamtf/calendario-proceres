<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nombre' => 'Administrador', 'nivel_jerarquico' => 1, 'descripcion' => 'Control total del sistema.'],
            ['nombre' => 'Obispo', 'nivel_jerarquico' => 2, 'descripcion' => 'Miembro del Consejo de Obispado. Aprueba/rechaza actividades.'],
            ['nombre' => 'Primer Consejero', 'nivel_jerarquico' => 2, 'descripcion' => 'Miembro del Consejo de Obispado. Aprueba/rechaza actividades.'],
            ['nombre' => 'Segundo Consejero', 'nivel_jerarquico' => 2, 'descripcion' => 'Miembro del Consejo de Obispado. Aprueba/rechaza actividades.'],
            ['nombre' => 'Secretario de Barrio', 'nivel_jerarquico' => 3, 'descripcion' => 'Visualización global y reportes. Respaldo de Ejecutivo/Finanzas.'],
            ['nombre' => 'Secretario Ejecutivo', 'nivel_jerarquico' => 4, 'descripcion' => 'Revisa actividades, administra calendario y trimestres, genera reportes.'],
            ['nombre' => 'Secretario de Finanzas', 'nivel_jerarquico' => 4, 'descripcion' => 'Asigna el presupuesto por organización y trimestre.'],
            ['nombre' => 'Presidencia', 'nivel_jerarquico' => 5, 'descripcion' => 'Gestiona actividades solo de su propia organización (ver users.organizacion_id).'],
        ];

        foreach ($roles as $rol) {
            Role::updateOrCreate(['nombre' => $rol['nombre']], $rol);
        }
    }
}
