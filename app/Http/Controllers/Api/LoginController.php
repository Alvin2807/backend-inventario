<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Login\InciarSesionRequest;
use App\Http\Requests\Login\RegistrarRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegistrarRequest $request)
    {
       //Registrar usuario
       try {
        DB::beginTransaction();

        $user = new User();
        $user->name         = strtoupper($request->input('name'));
        $user->apellido     = strtoupper($request->input('apellido'));
        $user->usuario      = strtoupper($request->input('usuario'));
        $user->fk_despacho  = strtoupper($request->input('fk_despacho'));
        $user->fk_rol       = strtoupper($request->input('fk_rol'));
        $user->email        = strtolower($request->input('email'));
        $user->password     = Hash::make($request->input('password'));
        $user->save();

        $token  = $user->createToken('auth_token')->plainTextToken;
        $cookie = cookie('token', $token, 60 * 24);// 1 día
        DB::commit();

        return response()->json([
         "ok"   =>true,
         //"data" => new UserResource($user),
         "exitoso" =>'Se guardo satisfactoriamente'
        ])->withCookie($cookie);
     } catch (\Exception $error) {
         DB::rollBack();
         return response()->json([
             'ok'   =>false,
             "data" =>$error->getMessage(),
             "error" =>'Hubo un error consulte con el Administrador del sistema'
         ]);
     }

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function inciarSesion(User $user, InciarSesionRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $user = User::where('usuario', $data['usuario'])->first();
 
            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'message' => 'Usuario o contraseña incorrecta!'
                ]);
            }
 
            $token = $user->createToken('auth_token')->plainTextToken;
            $cookie = cookie('token', $token, 60 * 24); // 1 day
            DB::commit();
            return response()->json([
             'data' => $user,
             'token' =>$token,
             "ok" =>true,
             "exitoso" =>'Has ingresado satisfactoriamente'
         ])->withCookie($cookie);
 
         } catch (\Exception $th) {
             DB::rollBack();
             return response()->json([
                 "ok"   =>false,
                 "data" =>$th->getMessage(),
                 "error" =>'Hubo un error consulte con el Administrador del sistema'
             ]);
         }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
