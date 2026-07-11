<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FechaEspecial extends Model
{
    public $timestamps = false;

    protected $fillable = ['tipo_fecha_especial_id', 'nombre', 'fecha_inicio', 'fecha_fin', 'descripcion', 'creado_por'];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoFechaEspecial::class, 'tipo_fecha_especial_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}
