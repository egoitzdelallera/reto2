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
            $table->string('ubicacion', 100);
            $table->bigInteger('id_taller');
            $table->bigInteger('id_campus');
            $table->enum('prioridad', ['Alta', 'Media', 'Baja']);
            $table->enum('estado', ['Habilitado', 'Deshabilitado']);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->primary('id_maquina');
            $table->foreign('id_taller')->references('id_taller')->on('talleres')->onDelete('cascade');
            $table->foreign('id_campus')->references('id_campus')->on('campus')->onDelete('cascade');
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
