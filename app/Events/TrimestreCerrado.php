<?php

namespace App\Events;

use App\Models\Trimestre;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TrimestreCerrado
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Trimestre $trimestre,
        public User $usuario,
    ) {
    }
}
