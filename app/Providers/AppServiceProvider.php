<?php

namespace App\Providers;

use App\Enums\RolUsuario;
use App\Models\Actividad;
use App\Models\FechaEspecial;
use App\Models\Organizacion;
use App\Models\PresupuestoOrganizacion;
use App\Models\Trimestre;
use App\Models\User;
use App\Policies\ActividadPolicy;
use App\Policies\FechaEspecialPolicy;
use App\Policies\OrganizacionPolicy;
use App\Policies\PresupuestoOrganizacionPolicy;
use App\Policies\TrimestrePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ===== Registro de Policies =====
        Gate::policy(Actividad::class, ActividadPolicy::class);
        Gate::policy(Trimestre::class, TrimestrePolicy::class);
        Gate::policy(Organizacion::class, OrganizacionPolicy::class);
        Gate::policy(PresupuestoOrganizacion::class, PresupuestoOrganizacionPolicy::class);
        Gate::policy(FechaEspecial::class, FechaEspecialPolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        // ===== Administrador pasa por encima de TODAS las Policies =====
        // Evita repetir "si es Administrador, permitir todo" en cada método de cada Policy (Fase 3, 2.7).
        Gate::before(function (User $user, string $ability) {
            return $user->rolEnum() === RolUsuario::Administrador ? true : null;
        });

        // ===== Gate suelto para Reportes (no es un modelo Eloquent) =====
        // Todos los roles excepto Presidencia (Fase 1: reportes no están en su navegación).
        Gate::define('ver-reportes', function (User $user) {
            return $user->rolEnum() !== RolUsuario::Presidencia;
        });

        // ===== Gate suelto para Configuración (nombre del barrio, catálogos, cierre) =====
        Gate::define('gestionar-configuracion', function (User $user) {
            return $user->rolEnum() === RolUsuario::Administrador;
        });
    }
}
