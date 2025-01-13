<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigInteger('id_usuario')->autoIncrement();
            $table->string('nombre', 100);
            $table->string('correo', 100)->unique();
            $table->string('contrasena', 100);
            $table->enum('rol', ['Administrador', 'Operario', 'Tecnico']);
            $table->enum('estado', ['Habilitado', 'Deshabilitado']);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->primary('id_usuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
