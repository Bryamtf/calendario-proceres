<?php

namespace App\Enums;

/**
 * Mapea por `nombre` a una fila de la tabla catálogo `roles`.
 */
enum RolUsuario: string
{
    case Administrador = 'Administrador';
    case Obispo = 'Obispo';
    case PrimerConsejero = 'Primer Consejero';
    case SegundoConsejero = 'Segundo Consejero';
    case SecretarioBarrio = 'Secretario de Barrio';
    case SecretarioEjecutivo = 'Secretario Ejecutivo';
    case SecretarioFinanzas = 'Secretario de Finanzas';
    case Presidencia = 'Presidencia';

    /** Miembros del Consejo de Obispado: cualquiera de los 3 puede aprobar/rechazar. */
    public function esConsejoObispado(): bool
    {
        return in_array($this, [self::Obispo, self::PrimerConsejero, self::SegundoConsejero], true);
    }

    /** Roles que pueden editar el monto asignado a una organización (confirmado en Fase 4). */
    public function puedeEditarPresupuestoAsignado(): bool
    {
        return in_array($this, [
            self::Administrador,
            self::Obispo,
            self::PrimerConsejero,
            self::SegundoConsejero,
            self::SecretarioBarrio,
            self::SecretarioEjecutivo,
            self::SecretarioFinanzas,
        ], true);
    }

    /** Roles con visibilidad de TODOS los presupuestos (no solo el de su organización). */
    public function veTodosLosPresupuestos(): bool
    {
        return $this !== self::Presidencia;
    }
}
