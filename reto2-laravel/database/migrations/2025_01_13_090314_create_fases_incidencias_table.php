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
        Schema::create('fases_incidencias', function (Blueprint $table) {
            $table->bigInteger('id_fase_incidencia')->autoIncrement();
            $table->bigInteger('id_incidencia');
            $table->string('nombre', 100);
            $table->string('descripcion', 300);
            $table->enum('estado', ['Pendiente', 'En proceso', 'Completada']);
            $table->timestamp('fecha_inicio')->useCurrent();
            $table->timestamp('fecha_fin')->nullable();
            $table->primary('id_fase_incidencia');
            $table->foreign('id_incidencia')->references('id_incidencia')->on('incidencias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fases_incidencias');
    }
};
