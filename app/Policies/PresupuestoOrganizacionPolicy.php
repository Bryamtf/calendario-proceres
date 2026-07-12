<?php

namespace App\Policies;

use App\Models\PresupuestoOrganizacion;
use App\Models\User;

class PresupuestoOrganizacionPolicy
{
    /** Presidencia solo ve el de su propia organización; el resto de roles ven todos (Fase 1/4). */
    public function view(User $user, PresupuestoOrganizacion $presupuesto): bool
    {
        if ($user->rolEnum()->veTodosLosPresupuestos()) {
            return true;
        }

        return $user->organizacion_id === $presupuesto->organizacion_id;
    }

    /**
     * Editar el monto asignado: Administrador, Consejo de Obispado, Secretario de Barrio,
     * Secretario Ejecutivo y Secretario de Finanzas (confirmado en Fase 4) — nunca Presidencia.
     */
    public function create(User $user): bool
    {
        return $user->rolEnum()->puedeEditarPresupuestoAsignado();
    }

    public function update(User $user, PresupuestoOrganizacion $presupuesto): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, PresupuestoOrganizacion $presupuesto): bool
    {
        return false;
    }
}
