<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'correo',
        'rol',
        'estado',
        'imagen_perfil',
        'id_campus'
    ];

    protected $hidden = [
        'password',
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

    public function incidenciasComoOperario()
    {
        return $this->hasMany(Incidencia::class, 'id_usuario', 'id_usuario')->where('rol', 'Operario');
    }

    public function incidenciasComoTecnico()
    {
        return $this->hasManyThrough(
            Incidencia::class,
            TecnicosFasesIncidencia::class,
            'id_tecnico',
            'id_incidencia',
            'id_usuario',
            'id_fase_incidencia'
        )->where('rol', 'Tecnico');
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Implementar el método requerido por JWTSubject
     */
    public function getJWTIdentifier()
    {
        // Devuelve el identificador único de usuario (en tu caso, 'id_usuario')
        return $this->getKey();
    }

    /**
     * Implementar el método requerido por JWTSubject para reclamar información adicional
     */
    public function getJWTCustomClaims()
    {
        // Puedes agregar reclamaciones personalizadas si lo necesitas, por ejemplo:
        return [
            'rol' => $this->rol, // Añade el rol del usuario al token (ejemplo)
        ];
    }
}
