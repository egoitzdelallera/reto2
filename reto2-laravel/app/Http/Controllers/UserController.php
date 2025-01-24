<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Correo o contraseña inválidos'], 401);
        }

        $user = auth()->user();

        $customClaims = [
            'id_usuario' => $user->id_usuario,
            'nombre' => $user->nombre,
            'apellido' => $user->apellido,
            'correo' => $user->correo,
            'rol' => $user->rol,
            'estado' => $user->estado,
            'imagen_perfil' => $user->imagen_perfil,
            'id_campus' => $user->id_campus,
        ];

        $token = JWTAuth::claims($customClaims)->attempt($credentials);

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function logout(Request $request)
    {
        try {
            // Obtener el token del encabezado de la solicitud
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json(['error' => 'Token no encontrado'], 400);
            }

            // Invalidar el token
            JWTAuth::invalidate($token);

            return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Error al cerrar sesión, token no procesable'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error inesperado'], 500);
        }
    }


  public function index(Request $request)
    {
    $users = User::with('campus:id_campus,nombre')
         ->select('id_usuario', 'nombre', 'apellido', 'correo', 'rol', 'estado', 'imagen_perfil', 'id_campus')
         ->get();
        
     $users->each(function ($user) {
           $user->campus = $user->campus ? $user->campus->nombre : '-';
     });
 
      return response()->json($users);
    }

   public function user(Request $request)
    {
         $users = User::with('campus:id_campus,nombre')
         ->select('id_usuario', 'nombre', 'apellido', 'correo', 'rol', 'estado', 'imagen_perfil', 'id_campus')
         ->get();
        
      $users->each(function ($user) {
           $user->campus = $user->campus ? $user->campus->nombre : '-';
     });
         return response()->json($users);
    }


    public function update(Request $request, $id)
    {
        $user = User::find($id);
    
          if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
    
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:users,correo,' . $id . ',id_usuario',
            'rol' => 'required|in:Administrador,Técnico,Operario',
            'password' => 'nullable|string|min:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->nombre = $request->input('nombre');
        $user->apellido = $request->input('apellido');
        $user->correo = $request->input('correo');
        $user->rol = $request->input('rol');
    
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return response()->json(['message' => 'Usuario actualizado correctamente', 'user' => $user]);
    }
    public function toggleStatus(Request $request, $id)
    {
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
    
        $user->estado = $user->estado ? "Habilitado" : "Deshabilitado";
        $user->save();
    
        return response()->json(['message' => 'Estado de usuario actualizado correctamente']);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
              'apellido' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:users,correo',
            'rol' => 'required|in:Administrador,Técnico,Operario',
            'password' => 'required|string|min:6',
             'id_campus' => 'nullable|integer'
        ]);
 
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
 
        $user = new User();
        $user->nombre = $request->input('nombre');
        $user->apellido = $request->input('apellido');
        $user->correo = $request->input('correo');
        $user->rol = $request->input('rol');
        $user->password = Hash::make($request->input('password'));
        $user->estado = 'Habilitado';
        $user->id_campus = $request->input('id_campus');
 
        $user->save();
 
        return response()->json(['message' => 'Usuario creado correctamente', 'user' => $user]);
    }
}