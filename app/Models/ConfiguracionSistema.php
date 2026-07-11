<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Tabla singleton: siempre debe existir exactamente 1 fila (creada por seeder). */
class ConfiguracionSistema extends Model
{
    protected $table = 'configuracion_sistema';

    protected $fillable = ['nombre_barrio', 'logo_path', 'dias_gracia_cierre_trimestre'];

    public static function obtener(): self
    {
        return static::firstOrFail();
    }
}
