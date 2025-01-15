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
        Schema::create('talleres', function (Blueprint $table) {
            $table->bigInteger('id_taller')->autoIncrement();
            $table->string('nombre', 100);
            $table->string('ubicacion', 100);
            $table->bigInteger('id_responsable');
            $table->enum('estado', ['Habilitado', 'Deshabilitado']);
            $table->primary('id_taller');
            $table->foreign('id_responsable')->references('id_usuario')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talleres');
    }
};
