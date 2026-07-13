<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacion_id')->constrained('organizaciones');
            $table->foreignId('trimestre_id')->constrained('trimestres');
            $table->string('nombre', 150);
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('lugar', 150);
            $table->text('objetivo');
            $table->text('descripcion')->nullable();
            $table->integer('asistencia_esperada')->nullable(); // estimado general, no nominal
            $table->integer('miembros_nuevos')->nullable(); // estimado independiente de actividad_participantes
            $table->integer('amigos_ensenanza')->nullable();
            $table->integer('miembros_menos_activos')->nullable();
            $table->boolean('solicita_presupuesto')->default(false);
            $table->foreignId('estado_actual_id')->constrained('estados_actividad');

            // Self-reference: si esta actividad nació de migrar una "No Procesada"
            // del trimestre anterior. Nullable porque la mayoría no lo son.
            $table->foreignId('actividad_origen_id')
                ->nullable()
                ->constrained('actividades');

            $table->foreignId('creado_por')->constrained('users');
            $table->foreignId('aprobado_por')->nullable()->constrained('users');
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
