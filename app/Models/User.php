<?php

namespace App\Models;

use App\Enums\RolUsuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'organizacion_id',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    // ===== Relaciones =====

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /** Solo tiene valor cuando role.nombre === 'Presidencia' (regla de Fase 2, validada en Form Request). */
    public function organizacion(): BelongsTo
    {
        return $this->belongsTo(Organizacion::class);
    }

    public function actividadesCreadas(): HasMany
    {
        return $this->hasMany(Actividad::class, 'creado_por');
    }

    public function actividadesAprobadas(): HasMany
    {
        return $this->hasMany(Actividad::class, 'aprobado_por');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(ComentarioActividad::class, 'usuario_id');
    }

    // ===== Helper del enum de rol =====

    public function rolEnum(): RolUsuario
    {
        return RolUsuario::from($this->role->nombre);
    }

    public function esConsejoObispado(): bool
    {
        return $this->rolEnum()->esConsejoObispado();
    }
}
