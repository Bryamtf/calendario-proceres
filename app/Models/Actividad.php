<?php

namespace App\Models;

use App\Enums\EstadoActividad as EstadoActividadEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Actividad extends Model
{
    protected $fillable = [
        'organizacion_id',
        'trimestre_id',
        'nombre',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'lugar',
        'objetivo',
        'descripcion',
        'asistencia_esperada',
        'miembros_nuevos',
        'amigos_ensenanza',
        'miembros_menos_activos',
        'solicita_presupuesto',
        'estado_actual_id',
        'actividad_origen_id',
        'creado_por',
        'aprobado_por',
        'fecha_aprobacion',
    ];

    protected $casts = [
        'fecha' => 'date',
        'solicita_presupuesto' => 'boolean',
        'fecha_aprobacion' => 'datetime',
    ];

    // ===== Relaciones =====

    public function organizacion(): BelongsTo
    {
        return $this->belongsTo(Organizacion::class);
    }

    public function trimestre(): BelongsTo
    {
        return $this->belongsTo(Trimestre::class);
    }

    public function estadoActual(): BelongsTo
    {
        return $this->belongsTo(EstadoActividadModelo::class, 'estado_actual_id');
    }

    public function actividadOrigen(): BelongsTo
    {
        return $this->belongsTo(self::class, 'actividad_origen_id');
    }

    /** Si esta actividad fue migrada a un trimestre posterior, aquí está esa nueva fila. */
    public function migracion(): HasMany
    {
        return $this->hasMany(self::class, 'actividad_origen_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function aprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function presupuestoItems(): HasMany
    {
        return $this->hasMany(ActividadPresupuestoItem::class);
    }

    public function participantes(): HasMany
    {
        return $this->hasMany(ActividadParticipante::class);
    }

    public function historialEstados(): HasMany
    {
        return $this->hasMany(HistorialEstadoActividad::class)->orderBy('fecha_cambio');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(ComentarioActividad::class)->orderBy('created_at');
    }

    public function recursos(): BelongsToMany
    {
        return $this->belongsToMany(Recurso::class, 'actividad_recurso')
            ->withPivot('detalle');
    }

    // ===== Scopes =====

    public function scopeConEstado(Builder $query, EstadoActividadEnum $estado): Builder
    {
        return $query->whereHas('estadoActual', fn(Builder $q) => $q->where('nombre', $estado->value));
    }

    public function scopePendientes(Builder $query): Builder
    {
        return $query->conEstado(EstadoActividadEnum::Pendiente);
    }

    public function scopeDelTrimestreActivo(Builder $query): Builder
    {
        return $query->whereHas('trimestre', fn(Builder $q) => $q->where('estado', 'activo'));
    }

    // ===== Helpers de presupuesto (modo híbrido: aproximado vs desglose) =====

    /** Monto total solicitado = SUM de las líneas, sin importar el modo usado. */
    public function montoTotalSolicitado(): float
    {
        return (float) $this->presupuestoItems()->sum('monto');
    }

    /** true si el presupuesto se cargó como una sola línea sin categoría (modo "aproximado"). */
    public function esPresupuestoAproximado(): bool
    {
        return $this->presupuestoItems()->count() === 1
            && $this->presupuestoItems()->whereNull('categoria_presupuesto_id')->exists();
    }

    // ===== Helpers de participación (modo híbrido: número estimado + nombres opcionales) =====

    public function nombresPorTipo(string $tipo): array
    {
        return $this->participantes()->where('tipo', $tipo)->pluck('nombre')->all();
    }

    // ===== Máquina de estados =====

    public function estadoActualEnum(): EstadoActividadEnum
    {
        return EstadoActividadEnum::from($this->estadoActual->nombre);
    }

    public function puedeTransicionarA(EstadoActividadEnum $nuevoEstado): bool
    {
        return $this->estadoActualEnum()->puedeTransicionarA($nuevoEstado);
    }
}
