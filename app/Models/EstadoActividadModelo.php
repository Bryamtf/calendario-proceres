<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoActividadModelo extends Model
{
    protected $table = 'estados_actividad';

    public $timestamps = false;

    protected $fillable = ['nombre', 'color', 'orden'];

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class, 'estado_actual_id');
    }
}
