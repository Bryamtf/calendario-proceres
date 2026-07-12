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
        Gate::policy(Actividad::class, ActividadPolicy::class);
        Gate::policy(Trimestre::class, TrimestrePolicy::class);
        Gate::policy(Organizacion::class, OrganizacionPolicy::class);
        Gate::policy(PresupuestoOrganizacion::class, PresupuestoOrganizacionPolicy::class);
        Gate::policy(FechaEspecial::class, FechaEspecialPolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        // ===== Administrador pasa por encima de TODAS las Policies =====

        Gate::before(function (User $user, string $ability) {
            return $user->rolEnum() === RolUsuario::Administrador ? true : null;
        });

        Gate::define('ver-reportes', function (User $user) {
            return $user->rolEnum() !== RolUsuario::Presidencia;
        });
    }
}
