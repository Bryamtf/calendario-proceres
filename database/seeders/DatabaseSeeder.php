<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            EstadoActividadSeeder::class,
            CatalogosSeeder::class,
            OrganizacionSeeder::class,
            ConfiguracionYAdminSeeder::class, // depende de RoleSeeder (necesita el rol Administrador)
        ]);
    }
}
