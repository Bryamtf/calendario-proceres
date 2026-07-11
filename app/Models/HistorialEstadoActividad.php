<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialEstadoActividad extends Model
{
    public $timestamps = false;

    protected $fillable = ['actividad_id', 'estado_anterior_id', 'estado_nuevo_id', 'usuario_id', 'comentario'];

    protected $casts = [
        'fecha_cambio' => 'datetime',
    ];

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }

    public function estadoAnterior(): BelongsTo
    {
        return $this->belongsTo(EstadoActividadModelo::class, 'estado_anterior_id');
    }

    public function estadoNuevo(): BelongsTo
    {
        return $this->belongsTo(EstadoActividadModelo::class, 'estado_nuevo_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
