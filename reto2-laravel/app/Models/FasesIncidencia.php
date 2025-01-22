<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FasesIncidencia extends Model
{
    use HasFactory;

    protected $table = 'fases_incidencias';
    protected $primaryKey = 'id_fase_incidencia';

    protected $fillable = [
        'id_incidencia',
        'nombre',
        'descripcion',
        'estado',
        'fecha_inicio',
        'fecha_fin'
    ];

    public function incidencia()
    {
        return $this->belongsTo(Incidencia::class, 'id_incidencia');
    }

    public function tecnicosFasesIncidencias()
    {
        return $this->hasMany(TecnicosFasesIncidencia::class, 'id_fase_incidencia');
    }
}
