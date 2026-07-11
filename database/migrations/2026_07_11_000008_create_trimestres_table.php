<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trimestres', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50); // Ej. "Q1 2026"
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('estado', 20)->default('activo'); // activo / cerrado
            $table->timestamps();
        });

        // Regla de negocio crítica: solo puede existir UN trimestre con estado='activo'.
        // Laravel no tiene helper nativo para índices únicos parciales, así que se
        // agrega vía SQL directo (específico de PostgreSQL).
        DB::statement('
            CREATE UNIQUE INDEX one_active_trimestre
            ON trimestres (estado)
            WHERE estado = \'activo\'
        ');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS one_active_trimestre');
        Schema::dropIfExists('trimestres');
    }
};
