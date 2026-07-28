<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            $table->string('nombre_solicitante', 150)->nullable()->after('aprobado_por');
            $table->string('nombre_cobrador', 150)->nullable()->after('nombre_solicitante');
        });
    }

    public function down(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            $table->dropColumn(['nombre_solicitante', 'nombre_cobrador']);
        });
    }
};
