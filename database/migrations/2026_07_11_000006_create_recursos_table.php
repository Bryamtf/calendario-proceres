<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique(); // Flyer, Equipo de sonido, Proyector, Cocina, Mesas, Sillas, Otro
            $table->string('estado', 20)->default('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recursos');
    }
};
