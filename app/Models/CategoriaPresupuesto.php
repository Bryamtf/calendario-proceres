<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class CategoriaPresupuesto extends Model
{
    protected $table = 'categorias_presupuesto';

    public $timestamps = false;

    protected $fillable = ['nombre', 'estado'];

    public function items(): HasMany
    {
        return $this->hasMany(ActividadPresupuestoItem::class);
    }

    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('estado', 'activo');
    }
}
