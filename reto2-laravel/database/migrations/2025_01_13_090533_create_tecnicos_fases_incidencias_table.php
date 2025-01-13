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
        Schema::create('tecnicos_fases_incidencias', function (Blueprint $table) {
            $table->bigInteger('id_fase_incidencia');
            $table->bigInteger('id_tecnico');
            $table->foreign('id_fase_incidencia')->references('id_fase_incidencia')->on('fases_incidencias')->onDelete('cascade');
            $table->foreign('id_tecnico')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tecnicos_fases_incidencias');
    }
};
