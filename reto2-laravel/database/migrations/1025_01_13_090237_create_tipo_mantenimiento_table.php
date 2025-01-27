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
        Schema::create('tipo_mantenimiento', function (Blueprint $table) {
            $table->bigInteger('id_tipo_mantenimiento')->autoIncrement();
            $table->string('nombre', 100);
            $table->string('descripcion', 300);
            $table->primary('id_tipo_mantenimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_mantenimiento');
    }
};
