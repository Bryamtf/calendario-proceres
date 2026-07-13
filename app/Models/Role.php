<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    public $timestamps = false;

    protected $fillable = ['nombre', 'nivel_jerarquico', 'descripcion'];

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
