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
        Schema::create('incidencias', function (Blueprint $table) {
            $table->bigInteger('id_incidencia')->autoIncrement();
            $table->bigInteger('id_maquina');
            $table->string('descripcion', 300);
            $table->enum('gravedad', ['Maquina en Marcha', 'Maquina parada', 'Aviso', 'Mantenimiento']);
            $table->bigInteger('id_tipo_mantenimiento')->nullable();
            $table->bigInteger('id_tipo_averia')->nullable();
            $table->enum('estado', ['Abierta', 'En progreso', 'Resuelta', 'Cancelada']);
            $table->enum('frecuencia', ['Diaria', 'Semanal', 'Mensual', 'Anual'])->nullable();
            $table->string('multimedia', 300)->nullable();
            $table->timestamp('fecha_reporte')->useCurrent();
            $table->timestamp('fecha_cierre')->nullable();
            $table->primary('id_incidencia');
            $table->foreign('id_maquina')->references('id_maquina')->on('maquinas')->onDelete('cascade');
            $table->foreign('id_tipo_mantenimiento')->references('id_tipo_mantenimiento')->on('tipo_mantenimiento')->onDelete('cascade');
            $table->foreign('id_tipo_averia')->references('id_tipo_averia')->on('tipo_averia')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};
