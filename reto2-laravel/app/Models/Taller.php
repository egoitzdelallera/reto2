<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Taller extends Model
{
    use HasFactory;

    protected $table = 'talleres';
    protected $primaryKey = 'id_taller'; // Definir la clave primaria

    protected $fillable = [
        'nombre',
        'ubicacion',
        'id_responsable',
        'estado',
        'id_campus'
    ];

   
    
    public function responsable()
    {
        return $this->belongsTo(User::class, 'id_responsable');
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'id_campus');
    }
    
    public function maquinas()
    {
        return $this->hasMany(Maquina::class, 'id_taller');
    }
}