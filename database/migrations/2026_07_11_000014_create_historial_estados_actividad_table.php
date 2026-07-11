<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_estados_actividad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividades')->cascadeOnDelete();

            // Null en el primer registro (creación de la actividad)
            $table->foreignId('estado_anterior_id')
                ->nullable()
                ->constrained('estados_actividad');

            $table->foreignId('estado_nuevo_id')->constrained('estados_actividad');
            $table->foreignId('usuario_id')->constrained('users'); // quién ejecutó el cambio
            $table->text('comentario')->nullable();
            $table->timestamp('fecha_cambio')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_estados_actividad');
    }
};
