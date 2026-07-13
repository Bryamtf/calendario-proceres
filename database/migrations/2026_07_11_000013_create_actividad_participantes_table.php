<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividad_participantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividades')->cascadeOnDelete();

            // 3 valores fijos de negocio, no un catálogo administrable:
            // 'miembro_nuevo' | 'amigo_ensenanza' | 'menos_activo'
            // Se valida en el Form Request, no como enum de PostgreSQL.
            $table->string('tipo', 20);

            $table->string('nombre', 150);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividad_participantes');
    }
};
