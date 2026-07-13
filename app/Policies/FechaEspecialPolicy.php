<?php

namespace App\Policies;

use App\Enums\RolUsuario;
use App\Models\FechaEspecial;
use App\Models\User;

class FechaEspecialPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, FechaEspecial $fechaEspecial): bool
    {
        return true;
    }

    /** Administra el calendario: Administrador o Secretario Ejecutivo. */
    public function create(User $user): bool
    {
        return in_array($user->rolEnum(), [RolUsuario::Administrador, RolUsuario::SecretarioEjecutivo], true);
    }

    public function update(User $user, FechaEspecial $fechaEspecial): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, FechaEspecial $fechaEspecial): bool
    {
        return $this->create($user);
    }
}
