<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    use HasFactory;

    protected $table = 'incidencias';

    protected $fillable = [
        'id_maquina',
        'descripcion',
        'tipo',
        'id_tipo_mantenimiento',
        'id_tipo_averia',
        'estado',
        'frecuencia',
        'multimedia',
        'fecha_reporte',
        'fecha_cierre'
    ];

    public function maquina()
    {
        return $this->belongsTo(Maquina::class, 'id_maquina');
    }

    public function tipoMantenimiento()
    {
        return $this->belongsTo(TipoMantenimiento::class, 'id_tipo_mantenimiento');
    }

    public function tipoAveria()
    {
        return $this->belongsTo(TipoAveria::class, 'id_tipo_averia');
    }

    public function fasesIncidencias()
    {
        return $this->hasMany(FasesIncidencia::class, 'id_incidencia');
    }
}
