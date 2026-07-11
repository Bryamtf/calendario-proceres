<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Organizacion extends Model
{
    protected $fillable = ['nombre', 'color', 'estado'];

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }

    public function presupuestos(): HasMany
    {
        return $this->hasMany(PresupuestoOrganizacion::class);
    }

    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('estado', 'activo');
    }

    /** Presupuesto asignado/solicitado/disponible de esta organización en un trimestre dado. */
    public function presupuestoEnTrimestre(int $trimestreId): ?PresupuestoOrganizacion
    {
        return $this->presupuestos()->where('trimestre_id', $trimestreId)->first();
    }
}
