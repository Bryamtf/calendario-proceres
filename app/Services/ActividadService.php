<?php

namespace App\Services;

use App\Enums\EstadoActividad;
use App\Models\Actividad;
use App\Models\EstadoActividadModelo;
use App\Models\Trimestre;
use App\Models\User;
use App\Events\ActividadEstadoCambiado;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ActividadService
{
    /**
     * Crea una actividad completa: datos generales + participantes nombrados (opcional)
     * + líneas de presupuesto (modo híbrido) + recursos. Todo en una transacción.
     */
    public function crear(array $datos, User $creador): Actividad
    {
        return DB::transaction(function () use ($datos, $creador) {
            $trimestreActivo = Trimestre::obtenerActivo();

            if (!$trimestreActivo) {
                throw ValidationException::withMessages([
                    'trimestre' => 'No hay un trimestre activo. Contacta al Administrador.',
                ]);
            }

            $estadoPendiente = EstadoActividadModelo::where('nombre', EstadoActividad::Pendiente->value)->firstOrFail();

            $actividad = Actividad::create([
                'organizacion_id' => $datos['organizacion_id'] ?? $creador->organizacion_id,
                'trimestre_id' => $trimestreActivo->id,
                'nombre' => $datos['nombre'],
                'fecha' => $datos['fecha'],
                'hora_inicio' => $datos['hora_inicio'],
                'hora_fin' => $datos['hora_fin'],
                'lugar' => $datos['lugar'],
                'objetivo' => $datos['objetivo'],
                'descripcion' => $datos['descripcion'] ?? null,
                'asistencia_esperada' => $datos['asistencia_esperada'] ?? null,
                'miembros_nuevos' => $datos['miembros_nuevos'] ?? null,
                'amigos_ensenanza' => $datos['amigos_ensenanza'] ?? null,
                'miembros_menos_activos' => $datos['miembros_menos_activos'] ?? null,
                'solicita_presupuesto' => $datos['solicita_presupuesto'] ?? false,
                'estado_actual_id' => $estadoPendiente->id,
                'creado_por' => $creador->id,
            ]);

            foreach ($datos['participantes'] ?? [] as $participante) {
                $actividad->participantes()->create([
                    'tipo' => $participante['tipo'],
                    'nombre' => $participante['nombre'],
                    'created_at' => now(),
                ]);
            }

            if ($actividad->solicita_presupuesto) {
                foreach ($datos['presupuesto_items'] ?? [] as $item) {
                    $actividad->presupuestoItems()->create([
                        'categoria_presupuesto_id' => $item['categoria_presupuesto_id'] ?? null,
                        'monto' => $item['monto'],
                        'justificacion' => $item['justificacion'] ?? null,
                    ]);
                }
            }

            foreach ($datos['recursos'] ?? [] as $recursoId) {
                $actividad->recursos()->attach($recursoId, [
                    'detalle' => $datos['recursos_detalle'][$recursoId] ?? null,
                ]);
            }

            event(new ActividadEstadoCambiado($actividad, null, EstadoActividad::Pendiente, $creador));

            CalendarioService::invalidarCache($trimestreActivo->id);

            return $actividad->fresh(['participantes', 'presupuestoItems', 'recursos']);
        });
    }

    /**
     * Aprobar o rechazar es la MISMA operación desde la perspectiva del sistema:
     * un miembro del Consejo de Obispado transiciona el estado, en representación
     * del acuerdo tomado en la reunión (Fase 1).
     */
    public function aprobar(Actividad $actividad, User $usuario): Actividad
    {
        return $this->transicionar($actividad, EstadoActividad::Aprobada, $usuario, null, [
            'aprobado_por' => $usuario->id,
            'fecha_aprobacion' => now(),
        ]);
    }

    public function rechazar(Actividad $actividad, User $usuario, string $motivo): Actividad
    {
        return $this->transicionar($actividad, EstadoActividad::Rechazada, $usuario, $motivo);
    }

    public function marcarRealizada(Actividad $actividad, User $usuario): Actividad
    {
        return $this->transicionar($actividad, EstadoActividad::Realizada, $usuario);
    }

    public function cancelar(Actividad $actividad, User $usuario, string $motivo): Actividad
    {
        return $this->transicionar($actividad, EstadoActividad::Cancelada, $usuario, $motivo);
    }

    /**
     * Migra una actividad "No Procesada" hacia el trimestre activo: crea una
     * NUEVA fila (no reabre la original), vinculada vía actividad_origen_id (Fase 1/2).
     */
    public function migrarANuevoTrimestre(Actividad $original, User $usuario): Actividad
    {
        $trimestreActivo = Trimestre::obtenerActivo();

        if (!$trimestreActivo) {
            throw ValidationException::withMessages([
                'trimestre' => 'No hay un trimestre activo para migrar la actividad.',
            ]);
        }

        return DB::transaction(function () use ($original, $usuario, $trimestreActivo) {
            $estadoPendiente = EstadoActividadModelo::where('nombre', EstadoActividad::Pendiente->value)->firstOrFail();

            $nueva = Actividad::create([
                'organizacion_id' => $original->organizacion_id,
                'trimestre_id' => $trimestreActivo->id,
                'nombre' => $original->nombre,
                'fecha' => $original->fecha,
                'hora_inicio' => $original->hora_inicio,
                'hora_fin' => $original->hora_fin,
                'lugar' => $original->lugar,
                'objetivo' => $original->objetivo,
                'descripcion' => $original->descripcion,
                'asistencia_esperada' => $original->asistencia_esperada,
                'miembros_nuevos' => $original->miembros_nuevos,
                'amigos_ensenanza' => $original->amigos_ensenanza,
                'miembros_menos_activos' => $original->miembros_menos_activos,
                'solicita_presupuesto' => $original->solicita_presupuesto,
                'estado_actual_id' => $estadoPendiente->id,
                'actividad_origen_id' => $original->id,
                'creado_por' => $usuario->id,
            ]);

            foreach ($original->presupuestoItems as $item) {
                $nueva->presupuestoItems()->create([
                    'categoria_presupuesto_id' => $item->categoria_presupuesto_id,
                    'monto' => $item->monto,
                    'justificacion' => $item->justificacion,
                ]);
            }

            event(new ActividadEstadoCambiado($nueva, null, EstadoActividad::Pendiente, $usuario, 'Migrada desde trimestre anterior'));

            CalendarioService::invalidarCache($trimestreActivo->id);

            return $nueva;
        });
    }

    private function transicionar(
        Actividad $actividad,
        EstadoActividad $nuevoEstado,
        User $usuario,
        ?string $comentario = null,
        array $atributosExtra = []
    ): Actividad {
        $estadoAnterior = $actividad->estadoActualEnum();

        if (!$estadoAnterior->puedeTransicionarA($nuevoEstado)) {
            throw ValidationException::withMessages([
                'estado' => "No se puede pasar de \"{$estadoAnterior->value}\" a \"{$nuevoEstado->value}\".",
            ]);
        }

        return DB::transaction(function () use ($actividad, $estadoAnterior, $nuevoEstado, $usuario, $comentario, $atributosExtra) {
            $estadoModelo = EstadoActividadModelo::where('nombre', $nuevoEstado->value)->firstOrFail();

            $actividad->update(array_merge(
                ['estado_actual_id' => $estadoModelo->id],
                $atributosExtra
            ));

            event(new ActividadEstadoCambiado($actividad, $estadoAnterior, $nuevoEstado, $usuario, $comentario));

            CalendarioService::invalidarCache($actividad->trimestre_id);

            return $actividad->fresh();
        });
    }
}
