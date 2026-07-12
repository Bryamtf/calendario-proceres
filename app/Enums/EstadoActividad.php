<?php

namespace App\Enums;

enum EstadoActividad: string
{
    case Pendiente = 'Pendiente';
    case Aprobada = 'Aprobada';
    case Rechazada = 'Rechazada';
    case Cancelada = 'Cancelada';
    case Realizada = 'Realizada';
    case NoProcesada = 'No Procesada';

    public function puedeTransicionarA(self $nuevo): bool
    {
        return match ($this) {
            self::Pendiente => in_array($nuevo, [self::Aprobada, self::Rechazada, self::NoProcesada], true),
            self::Aprobada => in_array($nuevo, [self::Realizada, self::Cancelada], true),
            self::Rechazada => $nuevo === self::Pendiente,
            default => false,
        };
    }

    public function esTerminal(): bool
    {
        return in_array($this, [self::Rechazada, self::Cancelada, self::Realizada, self::NoProcesada], true);
    }

    public function colorSugerido(): string
    {
        return match ($this) {
            self::Pendiente => '#C08A3E',
            self::Aprobada, self::Realizada => '#5B7A5D',
            self::Rechazada, self::Cancelada => '#A64B3F',
            self::NoProcesada => '#26282B',
        };
    }
}
