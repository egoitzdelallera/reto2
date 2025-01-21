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

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function index(Request $request)
    {
         $users = User::select('id_usuario', 'nombre', 'apellido', 'correo', 'rol', 'estado', 'imagen_perfil', 'id_campus')->get();
        return response()->json($users);
    }

     public function user(Request $request)
    {
         $users = User::select('id_usuario', 'nombre', 'apellido', 'correo', 'rol', 'estado', 'imagen_perfil','id_campus')->get();
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
             'rol' => 'required|in:Admin,Técnico,Operario',
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
            'rol' => 'required|in:Admin,Técnico,Operario',
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
         $user->estado = 1;
         $user->id_campus = $request->input('id_campus');

        $user->save();

        return response()->json(['message' => 'Usuario creado correctamente', 'user' => $user]);
    }
}