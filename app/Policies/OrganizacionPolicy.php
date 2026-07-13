<?php

namespace App\Policies;

use App\Models\Organizacion;
use App\Models\User;
use App\Enums\RolUsuario;

class OrganizacionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Organizacion $organizacion): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->rolEnum() === RolUsuario::Administrador;
    }

    public function update(User $user, Organizacion $organizacion): bool
    {
        return $this->create($user);
    }

    /** No se elimina (evita romper el historial de actividades ya vinculadas); solo se desactiva. */
    public function delete(User $user, Organizacion $organizacion): bool
    {
        return false;
    }
}
