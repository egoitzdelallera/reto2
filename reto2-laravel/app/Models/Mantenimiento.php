<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    use HasFactory;



    protected $table = 'incidencias'; // or your maintenance table
    protected $primaryKey = 'id_incidencia'; //or the primari key of the maintenance table
    public $timestamps = false;
    protected $fillable = [
        'descripcion',
        'gravedad',
        'estado',
        'id_maquina',
        'id_creador',
         'fecha_ini',
         'id_tipo_mantenimiento',
         'frecuencia'
    ];
     public function maquina()
    {
        return $this->belongsTo(Maquina::class, 'id_maquina');
    }
     public function creador()
     {
        return $this->belongsTo(User::class, 'id_creador', 'id_usuario');
     }
     public function tipoMantenimiento()
     {
        return $this->belongsTo(TipoMantenimiento::class, 'id_tipo_mantenimiento');
    }

}