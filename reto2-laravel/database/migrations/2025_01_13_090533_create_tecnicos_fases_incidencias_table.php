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
            $table->foreign('id_tecnico')->references('id_usuario')->on('users')->onDelete('cascade');
        });

        DB::unprepared('
            DELIMITER //
            
            CREATE TRIGGER checkear_rol_tecnico
            BEFORE INSERT ON tecnicos_fases_incidencias
            FOR EACH ROW
            BEGIN
                DECLARE user_role VARCHAR(255);
                
                -- Obtener el rol del usuario que estamos intentando asociar
                SELECT rol INTO user_role FROM users WHERE id_usuario = NEW.id_tecnico;

                -- Comprobar si el rol es "Técnico" o "Administrador"
                IF user_role NOT IN ("Técnico", "Administrador") THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "El usuario no tiene un rol válido para ser asignado a una fase.";
                END IF;
            END //
            
            DELIMITER ;
            ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tecnicos_fases_incidencias');

        DB::unprepared('DROP TRIGGER IF EXISTS checkear_rol_tecnico');
    }
};
