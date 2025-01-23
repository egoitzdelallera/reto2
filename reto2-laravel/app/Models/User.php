<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth; // Importa Auth
use Illuminate\Http\Request;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'correo',
        'apellido',
        'rol',
        'estado',
        'imagen_perfil',
        'id_campus',
        'password' // Añadido password a fillable
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


    public function getAllUsers(Request $request)
    {
      $query =  User::select('id_usuario', 'nombre', 'correo', 'rol', 'estado', 'imagen_perfil', 'id_campus');
         // Añadir ordenación si se especifica
        if ($request->has('orderBy') && $request->has('orderDir')) {
            $orderBy = $request->input('orderBy');
            $orderDir = $request->input('orderDir');
                $query->orderBy($orderBy, $orderDir);
            }else{
             $query->orderBy('id_usuario', 'asc');
            }
             $users = $query->get();
     
        return response()->json($users);
    }
}