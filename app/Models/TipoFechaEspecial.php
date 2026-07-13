<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoFechaEspecial extends Model
{
    protected $table = 'tipos_fecha_especial';

    public $timestamps = false;

    protected $fillable = ['nombre', 'color'];

    public function fechasEspeciales(): HasMany
    {
        return $this->hasMany(FechaEspecial::class);
    }
}
