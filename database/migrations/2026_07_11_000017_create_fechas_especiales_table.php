<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fechas_especiales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_fecha_especial_id')->constrained('tipos_fecha_especial');
            $table->string('nombre', 150);
            $table->date('fecha_inicio'); // soporta eventos de un día o rango
            $table->date('fecha_fin');
            $table->text('descripcion')->nullable();
            $table->foreignId('creado_por')->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fechas_especiales');
    }
};
