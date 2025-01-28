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
        Schema::create('campus', function (Blueprint $table) {
            $table->bigInteger('id_campus')->autoIncrement();
            $table->string('nombre', 100);
            $table->string('ubicacion', 200);
            $table->enum('estado', ['Habilitado', 'Deshabilitado']);
            $table->primary('id_campus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campus');
    }
};
