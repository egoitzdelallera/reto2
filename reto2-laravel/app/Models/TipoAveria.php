<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoAveria extends Model
{
    use HasFactory;

    protected $table = 'tipo_averia';

    protected $primaryKey = 'id_tipo_averia';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'estado'
    ];

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'id_tipo_averia');
    }
}
