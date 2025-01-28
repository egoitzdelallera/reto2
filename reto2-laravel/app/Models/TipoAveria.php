<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoAveria extends Model
{
    use HasFactory;

    protected $table = 'tipo_averia';
    protected $primaryKey = 'id_tipo_averia';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'estado' // Añadir estado al array fillable
    ];

    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class, 'id_tipo_averia');
    }
}