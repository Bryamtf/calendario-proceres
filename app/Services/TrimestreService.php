<?php

namespace App\Services;

use App\Events\TrimestreCerrado;
use App\Models\Trimestre;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TrimestreService
{
    /**
     * Abrir un trimestre nuevo requiere que no haya otro activo — el índice único
     * parcial de PostgreSQL (Fase 2) ya lo impide a nivel de BD, pero validamos antes
     * para dar un mensaje claro en vez de un error crudo de base de datos.
     */
    public function abrir(array $datos): Trimestre
    {
        if (Trimestre::obtenerActivo()) {
            throw ValidationException::withMessages([
                'trimestre' => 'Ya existe un trimestre activo. Ciérralo antes de abrir uno nuevo.',
            ]);
        }

        return Trimestre::create([
            'nombre' => $datos['nombre'],
            'fecha_inicio' => $datos['fecha_inicio'],
            'fecha_fin' => $datos['fecha_fin'],
            'estado' => 'activo',
        ]);
    }

    /**
     * Cierre manual o automático (mismo método, Fase 3, 2.6) — marca el trimestre
     * como cerrado y dispara el evento que migra las Pendientes a No Procesada.
     */
    public function cerrar(Trimestre $trimestre, User $usuario): Trimestre
    {
        if ($trimestre->estado === 'cerrado') {
            throw ValidationException::withMessages([
                'trimestre' => 'Este trimestre ya está cerrado.',
            ]);
        }

        return DB::transaction(function () use ($trimestre, $usuario) {
            $trimestre->update(['estado' => 'cerrado']);

            event(new TrimestreCerrado($trimestre, $usuario));

            return $trimestre->fresh();
        });
    }
}
