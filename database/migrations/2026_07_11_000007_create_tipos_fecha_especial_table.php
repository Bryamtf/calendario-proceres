<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_fecha_especial', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique(); // Conferencia General, Conferencia de Estaca, Feriado, Fecha Reservada, Otra
            $table->string('color', 7)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_fecha_especial');
    }
};
