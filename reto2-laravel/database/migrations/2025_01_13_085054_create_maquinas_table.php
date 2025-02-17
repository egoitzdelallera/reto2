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
        Schema::create('maquinas', function (Blueprint $table) {
            $table->bigInteger('id_maquina')->autoIncrement();
            $table->string('nombre', 100);
            $table->string('descripcion', 300);
            $table->bigInteger('id_taller');
            $table->enum('prioridad', ['Alta', 'Media', 'Baja']);
            $table->enum('estado', ['Habilitado', 'Deshabilitado']);
            $table->primary('id_maquina');
            $table->foreign('id_taller')->references('id_taller')->on('talleres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maquinas');
    }
};
