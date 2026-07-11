<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresupuestoOrganizacion extends Model
{
    protected $fillable = ['organizacion_id', 'trimestre_id', 'monto_asignado', 'creado_por'];

    protected $casts = [
        'monto_asignado' => 'decimal:2',
    ];

    public function organizacion(): BelongsTo
    {
        return $this->belongsTo(Organizacion::class);
    }

    public function trimestre(): BelongsTo
    {
        return $this->belongsTo(Trimestre::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    /** Suma de actividad_presupuesto_items de todas las actividades de esta organización/trimestre. */
    public function montoSolicitado(): float
    {
        return Actividad::where('organizacion_id', $this->organizacion_id)
            ->where('trimestre_id', $this->trimestre_id)
            ->join('actividad_presupuesto_items', 'actividades.id', '=', 'actividad_presupuesto_items.actividad_id')
            ->sum('actividad_presupuesto_items.monto');
    }

    public function montoDisponible(): float
    {
        return (float) $this->monto_asignado - $this->montoSolicitado();
    }
}
