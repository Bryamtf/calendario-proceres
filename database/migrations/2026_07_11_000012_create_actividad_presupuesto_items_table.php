<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividad_presupuesto_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividades')->cascadeOnDelete();

            // NULL = "monto aproximado / sin categorizar" (modo simple).
            // Con valor = una línea del desglose por rubro (modo detallado).
            $table->foreignId('categoria_presupuesto_id')
                ->nullable()
                ->constrained('categorias_presupuesto');

            $table->decimal('monto', 10, 2);
            $table->text('justificacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividad_presupuesto_items');
    }
};
