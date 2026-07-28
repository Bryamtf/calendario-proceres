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

    /** Genera el token la primera vez que se necesita (no existe hasta el primer uso). */
    public static function obtenerTokenPublico(): string
    {
        $config = static::obtener();

        if (! $config->link_publico_token) {
            $config->link_publico_token = \Illuminate\Support\Str::random(40);
            $config->save();
        }

        return $config->link_publico_token;
    }

    /** Invalida el link anterior — cualquiera que lo tuviera guardado deja de funcionar. */
    public static function regenerarTokenPublico(): string
    {
        $config = static::obtener();
        $config->link_publico_token = \Illuminate\Support\Str::random(40);
        $config->save();

        return $config->link_publico_token;
    }
}
