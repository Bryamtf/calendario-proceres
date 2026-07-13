<?php

namespace App\Policies;

use App\Enums\RolUsuario;
use App\Models\Trimestre;
use App\Models\User;

class TrimestrePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Trimestre $trimestre): bool
    {
        return true;
    }

    /** Abrir/cerrar trimestre manualmente: Administrador o Secretario Ejecutivo (administra calendario). */
    public function create(User $user): bool
    {
        return in_array($user->rolEnum(), [RolUsuario::Administrador, RolUsuario::SecretarioEjecutivo], true);
    }

    public function update(User $user, Trimestre $trimestre): bool
    {
        return $this->create($user);
    }

    public function cerrar(User $user, Trimestre $trimestre): bool
    {
        return $this->create($user);
    }

    /** Nunca se elimina un trimestre — conserva historial. */
    public function delete(User $user, Trimestre $trimestre): bool
    {
        return false;
    }
}
