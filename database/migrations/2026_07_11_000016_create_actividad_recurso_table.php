<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividad_recurso', function (Blueprint $table) {
            $table->foreignId('actividad_id')->constrained('actividades')->cascadeOnDelete();
            $table->foreignId('recurso_id')->constrained('recursos');
            $table->string('detalle', 255)->nullable(); // ej. cantidad de sillas, especificación de "Otro"

            $table->primary(['actividad_id', 'recurso_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividad_recurso');
    }
};
