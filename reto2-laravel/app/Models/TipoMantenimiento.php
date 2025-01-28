<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class TipoMantenimiento extends Model
{
    use HasFactory;

    protected $table = 'tipo_mantenimiento';
    protected $primaryKey = 'id_tipo_mantenimiento';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'estado',
        'descripcion'

    ];

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'id_tipo_mantenimiento');
    }
}
