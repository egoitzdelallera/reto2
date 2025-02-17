<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    use HasFactory;

    protected $table = 'campus';
    protected $primaryKey = 'id_campus';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'ubicacion',
        'estado'
    ];

    public function talleres()
    {
        return $this->hasMany(Taller::class, 'id_campus');
    }

    public function maquinas()
    {
        return $this->hasMany(Maquina::class, 'id_campus');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_campus');
    }
}