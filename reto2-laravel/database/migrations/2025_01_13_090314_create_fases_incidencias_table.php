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
            $table->string('descripcion', 300)->nullable();
            $table->enum('estado', ['Pendiente', 'En proceso', 'Completada']);
            $table->timestamp('fecha_inicio')->useCurrent();
            $table->timestamp('fecha_fin')->nullable();
            $table->primary('id_fase_incidencia');
            $table->foreign('id_incidencia')->references('id_incidencia')->on('incidencias')->onDelete('cascade');
        });

        DB::unprepared("
            CREATE TRIGGER before_crear_fase
            BEFORE INSERT ON fases_incidencias
            FOR EACH ROW
            BEGIN
                DECLARE fases_pendientes INT;
                SELECT COUNT(*) INTO fases_pendientes
                FROM fases_incidencias
                WHERE id_incidencia = NEW.id_incidencia AND estado != 'Completada';

                IF fases_pendientes > 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'No se pueden crear nuevas fases mientras haya fases pendientes.';
                END IF;
            END;
        ");

        DB::unprepared("
        CREATE TRIGGER before_fase_update
        BEFORE UPDATE ON fases_incidencias
        FOR EACH ROW
        BEGIN

            IF NEW.estado = 'Completada' THEN
                
                IF @current_user_id IS NULL THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'El ID del usuario no fue proporcionado.';
                END IF;

                -- Verificar si el usuario está asignado a la fase
                IF NOT EXISTS (
                    SELECT 1
                    FROM tecnicos_fases_incidencias
                    WHERE id_fase_incidencia = NEW.id_fase_incidencia
                    AND id_tecnico = user_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'No puedes finalizar esta fase porque no estás asignado a ella.';
                END IF;
            END IF;
        END;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fases_incidencias');

        DB::unprepared("DROP TRIGGER IF EXISTS before_crear_fase");
        DB::unprepared("DROP TRIGGER IF EXISTS before_fase_update");
    }
};
