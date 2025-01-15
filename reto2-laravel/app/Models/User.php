<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $fillable = [
        'nombre',
        'correo',
        'contrasena',
        'rol',
        'estado',
        'imagen_perfil',
        'id_campus'
    ];

    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'id_campus');
    }

    public function talleresResponsable()
    {
        return $this->hasMany(Taller::class, 'id_responsable');
    }

    public function tecnicosFasesIncidencias()
    {
        return $this->hasMany(TecnicosFasesIncidencia::class, 'id_tecnico');
    }
}
