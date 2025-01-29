<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMantenimiento extends Model
{
    use HasFactory;

    protected $table = 'tipo_mantenimiento';
    protected $primaryKey = 'id_tipo_mantenimiento';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'id_tipo_mantenimiento');
    }
}
