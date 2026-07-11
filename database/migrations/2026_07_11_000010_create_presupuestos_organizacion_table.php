<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presupuestos_organizacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacion_id')->constrained('organizaciones');
            $table->foreignId('trimestre_id')->constrained('trimestres');
            $table->decimal('monto_asignado', 10, 2);
            $table->foreignId('creado_por')->constrained('users'); // Secretario de Finanzas que lo cargó
            $table->timestamps();

            $table->unique(['organizacion_id', 'trimestre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuestos_organizacion');
    }
};
