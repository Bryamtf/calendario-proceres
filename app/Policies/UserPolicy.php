<?php

namespace App\Policies;

use App\Enums\RolUsuario;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->rolEnum() === RolUsuario::Administrador;
    }

    /** Cualquiera puede ver su propio perfil; la gestión de otros es solo del Administrador. */
    public function view(User $user, User $modelo): bool
    {
        return $user->is($modelo) || $user->rolEnum() === RolUsuario::Administrador;
    }

    public function create(User $user): bool
    {
        return $user->rolEnum() === RolUsuario::Administrador;
    }

    public function update(User $user, User $modelo): bool
    {
        return $user->rolEnum() === RolUsuario::Administrador;
    }

    /** No se elimina — se desactiva (campo `activo`), conserva el historial de quién creó/aprobó qué. */
    public function delete(User $user, User $modelo): bool
    {
        return false;
    }
}
