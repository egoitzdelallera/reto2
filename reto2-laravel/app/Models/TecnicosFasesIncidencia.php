<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TecnicosFasesIncidencia extends Model
{
    use HasFactory;

    protected $table = 'tecnicos_fases_incidencias';
    public $timestamps = false;

    protected $fillable = [
        'id_fase_incidencia',
        'id_tecnico',
    ];

    public function fase()
    {
        return $this->belongsTo(FasesIncidencia::class, 'id_fase_incidencia');
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'id_tecnico');
    }
}
