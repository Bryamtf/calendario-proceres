<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComentarioActividad extends Model
{
    public $timestamps = false;

    protected $table = 'comentarios_actividad';

    protected $fillable = ['actividad_id', 'usuario_id', 'comentario'];

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
