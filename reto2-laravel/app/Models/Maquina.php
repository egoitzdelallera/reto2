<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Maquina extends Model
{
    use HasFactory;

    protected $table = 'maquinas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'ubicacion',
        'id_taller',
        'estado'
    ];

    public function taller()
    {
        return $this->belongsTo(Taller::class, 'id_taller');
    }

    public function indidencias()
    {
        return $this->hasMany(Incidencia::class, 'id_maquina');
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'id_campus');
    }
}
