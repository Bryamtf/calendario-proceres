<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Trimestre extends Model
{
    protected $fillable = ['nombre', 'fecha_inicio', 'fecha_fin', 'estado'];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }

    public function presupuestos(): HasMany
    {
        return $this->hasMany(PresupuestoOrganizacion::class);
    }

    public function scopeActivo(Builder $query): Builder
    {
        return $query->where('estado', 'activo');
    }

    /** Solo puede haber uno; conveniencia para no repetir Trimestre::activo()->first() */
    public static function obtenerActivo(): ?self
    {
        return static::activo()->first();
    }

    /** Días transcurridos / total, para el indicador de avance del sidebar (Fase 4). */
    public function diaActual(): int
    {
        return max(1, Carbon::today()->diffInDays($this->fecha_inicio) + 1);
    }

    public function totalDias(): int
    {
        return Carbon::parse($this->fecha_inicio)->diffInDays(Carbon::parse($this->fecha_fin)) + 1;
    }

    /** Fecha límite para el cierre automático (Fase 3/4: días de gracia configurables). */
    public function fechaLimiteCierreAutomatico(): Carbon
    {
        $diasGracia = ConfiguracionSistema::obtener()->dias_gracia_cierre_trimestre;

        return Carbon::parse($this->fecha_fin)->addDays($diasGracia);
    }
}
