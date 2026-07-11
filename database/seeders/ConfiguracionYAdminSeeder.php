<?php

namespace Database\Seeders;

use App\Models\ConfiguracionSistema;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ConfiguracionYAdminSeeder extends Seeder
{
    public function run(): void
    {
        ConfiguracionSistema::firstOrCreate([], [
            'nombre_barrio' => 'Barrio Los Próceres',
            'logo_path' => null,
            'dias_gracia_cierre_trimestre' => 3,
        ]);

        $rolAdmin = Role::where('nombre', 'Administrador')->firstOrFail();

        User::firstOrCreate(
            ['email' => 'admin@barrio.test'],
            [
                'name' => 'Administrador del Sistema',
                'password' => Hash::make('password'), // CAMBIAR en producción
                'role_id' => $rolAdmin->id,
                'organizacion_id' => null,
                'activo' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
