<?php

namespace App\Policies;

use App\Enums\RolUsuario;
use App\Models\Actividad;
use App\Models\User;

class ActividadPolicy
{
    /** Todos los roles autenticados pueden ver el calendario/listado (visibilidad global). */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Actividad $actividad): bool
    {
        return true;
    }

    /** Solo Presidencia puede proponer, y únicamente para su propia organización. */
    public function create(User $user): bool
    {
        return $user->rolEnum() === RolUsuario::Presidencia;
    }

    /**
     * Editar campos "menores" (lugar, hora, descripción, recursos) sin nuevo ciclo de aprobación.
     * Solo la Presidencia dueña, y solo si es su organización.
     */
    public function update(User $user, Actividad $actividad): bool
    {
        return $user->rolEnum() === RolUsuario::Presidencia
            && $user->organizacion_id === $actividad->organizacion_id;
    }

    /**
     * Editar campos "mayores" (fecha, monto, organización) — regresa a Pendiente,
     * mismo dueño que puede hacer una edición menor.
     */
    public function updateCambioMayor(User $user, Actividad $actividad): bool
    {
        return $this->update($user, $actividad);
    }

    /** Nunca se elimina una actividad — regla de negocio de Fase 1 (siempre conservar historial). */
    public function delete(User $user, Actividad $actividad): bool
    {
        return false;
    }

    /** Solo el Consejo de Obispado puede aprobar o rechazar. */
    public function aprobar(User $user, Actividad $actividad): bool
    {
        return $user->esConsejoObispado();
    }

    public function rechazar(User $user, Actividad $actividad): bool
    {
        return $this->aprobar($user, $actividad);
    }

    /** Revisar y comentar sin decidir: Consejo de Obispado, Secretario Ejecutivo y Secretario de Barrio. */
    public function comentar(User $user, Actividad $actividad): bool
    {
        return $user->esConsejoObispado()
            || in_array($user->rolEnum(), [RolUsuario::SecretarioEjecutivo, RolUsuario::SecretarioBarrio], true);
    }

    /** Migrar una actividad "No Procesada" al nuevo trimestre: solo la Presidencia dueña. */
    public function migrarANuevoTrimestre(User $user, Actividad $actividad): bool
    {
        return $user->rolEnum() === RolUsuario::Presidencia
            && $user->organizacion_id === $actividad->organizacion_id;
    }
}
