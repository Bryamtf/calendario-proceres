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
        // Se calcula por timestamps en vez de diffInDays() directo: en Carbon 3 (Laravel 11+)
        // diffInDays() dejó de devolver siempre valor absoluto, y el signo según el orden
        // de los argumentos causaba que esto diera negativo (y por ende "día 1" siempre).
        $dias = (int) floor((Carbon::today()->timestamp - $this->fecha_inicio->copy()->startOfDay()->timestamp) / 86400);

        return max(1, $dias + 1);
    }

    public function totalDias(): int
    {
        $dias = (int) floor(($this->fecha_fin->copy()->startOfDay()->timestamp - $this->fecha_inicio->copy()->startOfDay()->timestamp) / 86400);

        return $dias + 1;
    }

    /** Fecha límite para el cierre automático (Fase 3/4: días de gracia configurables). */
    public function fechaLimiteCierreAutomatico(): Carbon
    {
        $diasGracia = ConfiguracionSistema::obtener()->dias_gracia_cierre_trimestre;

        return $this->fecha_fin->copy()->addDays($diasGracia);
    }
}
