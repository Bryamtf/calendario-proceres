<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Modificamos la tabla users :D

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->after('id')
                ->constrained('roles');

            $table->foreignId('organizacion_id')
                ->nullable()
                ->after('role_id')
                ->constrained('organizaciones');

            $table->boolean('activo')
                ->default(true)
                ->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organizacion_id');
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn('activo');
        });
    }
};
