<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Maquina extends Model
{
    use HasFactory;

    protected $table = 'maquinas';
    protected $primaryKey = 'id_maquina';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_taller', // Cambiado a id_taller
        'prioridad',
    ];

    public function taller()
    {
        return $this->belongsTo(Taller::class, 'id_taller'); // Relación con Taller
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'id_maquina');
    }
}