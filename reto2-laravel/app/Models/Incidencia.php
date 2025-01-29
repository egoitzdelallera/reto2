<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
   use HasFactory;
    protected $table = 'incidencias';
    protected $primaryKey = 'id_incidencia';
    public $timestamps = false;
    protected $fillable = [
        'descripcion',
        'gravedad',
        'estado',
        'id_maquina',
        'id_creador',
        'fecha_reporte',
        'id_tipo_averia',
        'id_tipo_mantenimiento',
        'multimedia',
        'frecuencia',
   ];

  public function maquina()
   {
       return $this->belongsTo(Maquina::class, 'id_maquina');
   }

 public function creador()
   {
       return $this->belongsTo(User::class, 'id_creador', 'id_usuario');
   }

 public function tecnico()
   {
     return $this->hasOneThrough(
       User::class,
       TecnicosFasesIncidencia::class,
       'id_fase_incidencia',
       'id_usuario',
       'id_incidencia',
       'id_tecnico'
       )->where('rol', 'Tecnico');
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
        return $this->hasMany(FasesIncidencia::class, 'id_incidencia', 'id_incidencia');
    }

    public function setFechaReporteAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['fecha_reporte'] = now();
        } else {
            $this->attributes['fecha_reporte'] = $value;
        }
    }
}
