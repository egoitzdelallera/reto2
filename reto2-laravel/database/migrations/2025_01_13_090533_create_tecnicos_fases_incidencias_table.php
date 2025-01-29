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
            CREATE TRIGGER checkear_rol_tecnico
            BEFORE INSERT ON tecnicos_fases_incidencias
            FOR EACH ROW
            BEGIN
                DECLARE user_role VARCHAR(255);
                
                -- Obtener el rol del usuario que estamos intentando asociar
                SELECT rol INTO user_role FROM users WHERE id_usuario = NEW.id_tecnico;

                -- Comprobar si el rol es "Técnico" o "Administrador"
                IF user_role NOT IN ("Tecnico", "Administrador") THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "El usuario no tiene un rol válido para ser asignado a una fase.";
                END IF;
            END;
        ');

        DB::unprepared('
            CREATE OR REPLACE TRIGGER before_asignar_persona_a_fase
            BEFORE INSERT ON tecnicos_fases_incidencias
            FOR EACH ROW
            BEGIN
                DECLARE fase_estado VARCHAR(255);
                DECLARE usuario_rol VARCHAR(255);

                SELECT estado INTO fase_estado
                FROM fases_incidencias
                WHERE id_fase_incidencia = NEW.id_fase_incidencia;

                SELECT rol INTO usuario_rol
                FROM users
                WHERE id_usuario = NEW.id_tecnico;

                IF fase_estado = "Completada" THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "No se pueden asignar personas a una fase que ya está completada.";
                END IF;

                -- Verificar si el usuario es un operario
                IF usuario_rol = "Operario" THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Los operarios no pueden asignarse a una fase.";
                END IF;
            END;

        ');

        DB::unprepared("
        CREATE TRIGGER verificar_fase_activa_tecnico
        BEFORE INSERT ON tecnicos_fases_incidencias
        FOR EACH ROW
        BEGIN
            -- Verificar si el técnico ya está asignado a una fase activa
            IF EXISTS (
                SELECT 1
                FROM tecnicos_fases_incidencias tfi
                JOIN fases_incidencias fi ON tfi.id_fase_incidencia = fi.id_fase_incidencia
                WHERE tfi.id_tecnico = NEW.id_tecnico
                AND fi.estado != 'Completada' -- Cambia 'estado' si usas otro campo para determinar si está activa
            ) THEN
                -- Lanzar un error si el técnico ya está asignado a una fase activa
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'El técnico ya está asignado a una fase activa. Solo puede estar en una a la vez.';
            END IF;
        END;
        ");

        DB::unprepared("
        CREATE TRIGGER actualizar_estado_incidencia
        AFTER INSERT ON tecnicos_fases_incidencias
        FOR EACH ROW
        BEGIN
            -- Verificar si la incidencia relacionada está en estado 'Abierta'
            DECLARE estado_actual VARCHAR(50);

            SELECT i.estado
            INTO estado_actual
            FROM incidencias i
            INNER JOIN fases_incidencias fi ON fi.id_incidencia = i.id_incidencia
            WHERE fi.id_fase_incidencia = NEW.id_fase_incidencia
            LIMIT 1;

            -- Si la incidencia está abierta, actualizarla a 'En progreso'
            IF estado_actual = 'Abierta' THEN
                UPDATE incidencias
                SET estado = 'En progreso'
                WHERE id_incidencia = (
                    SELECT id_incidencia
                    FROM fases_incidencias
                    WHERE id_fase_incidencia = NEW.id_fase_incidencia
                    LIMIT 1
                );
            END IF;
        END;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER actualizar_estado_fase
            AFTER INSERT ON tecnicos_fases_incidencias
            FOR EACH ROW
            BEGIN
                DECLARE estado_actual VARCHAR(50);

                -- Obtener el estado actual de la fase
                SELECT estado
                INTO estado_actual
                FROM fases_incidencias
                WHERE id_fase_incidencia = NEW.id_fase_incidencia;

                -- Si la fase está pendiente, cambiar su estado a 'En proceso'
                IF estado_actual = 'Pendiente' THEN
                    UPDATE fases_incidencias
                    SET estado = 'En proceso'
                    WHERE id_fase_incidencia = NEW.id_fase_incidencia;
                END IF;
            END;"
    );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tecnicos_fases_incidencias');

        DB::unprepared('DROP TRIGGER IF EXISTS checkear_rol_tecnico');
        DB::unprepared("DROP TRIGGER IF EXISTS before_asignar_persona_a_fase");
        DB::unprepared("DROP TRIGGER IF EXISTS verificar_fase_activa_tecnico");
        DB::unprepared("DROP TRIGGER IF EXISTS actualizar_estado_incidencia");
        DB::unprepared("DROP TRIGGER IF EXISTS actualizar_estado_fase");

    }
};
