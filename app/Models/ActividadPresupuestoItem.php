<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActividadPresupuestoItem extends Model
{
    protected $table = 'actividad_presupuesto_items';

    protected $fillable = ['actividad_id', 'categoria_presupuesto_id', 'monto', 'justificacion'];

    protected $casts = [
        'monto' => 'decimal:2',
    ];

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaPresupuesto::class, 'categoria_presupuesto_id');
    }

    /** NULL en categoria_presupuesto_id = modo "monto aproximado" (ver Fase 2). */
    public function esAproximado(): bool
    {
        return is_null($this->categoria_presupuesto_id);
    }
}
