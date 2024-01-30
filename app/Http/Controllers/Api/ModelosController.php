<?php

namespace App\Http\Controllers\Api;

use App\Models\Modelo;
use App\Http\Controllers\Controller;
use App\Models\VistaModeloMarca;
use Illuminate\Http\Request;
use App\Http\Requests\Modelos\StoreRequest;
use App\Models\Nomenclatura;
use Illuminate\Support\Facades\DB;

class ModelosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Mostrar modelos
       $vista_modelo = VistaModeloMarca::
       select('id_modelo','fk_marca','nombre_marca','nombre_modelo')
       ->get();
       return response()->json([
        "ok" =>true,
        "data"=>$vista_modelo
       ]);
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
    public function store(StoreRequest $request)
    {
        //Registrar modelos
        try {
            DB::beginTransaction();
            $fk_marca = $request->input('fk_marca');
            $usuario  = strtoupper($request->input('usuario'));
            $nombre_modelo = strtoupper($request->input('nombre_modelo'));
            $consultar = Modelo::
            select('id_modelo','nombre_modelo')
            ->where('nombre_modelo', $nombre_modelo)
            ->get();
            if (count($consultar) > 0) {
                return response()->json([
                    "ok" =>true,
                    "existe" =>'Ya existe el modelo '.$nombre_modelo
                ]);
            } else {
                $modelos = new Modelo();
                $modelos->fk_marca = $fk_marca;
                $modelos->nombre_modelo = $nombre_modelo;
                $modelos->usuario_crea = $usuario;
                $modelos->save();

                $nomenclatura = new Nomenclatura();
                $nomenclatura->fk_modelo = $modelos->id;
                $nomenclatura->nomenclatura = strtoupper($request->input('nomenclatura'));
                $nomenclatura->usuario_crea = $usuario;
                $nomenclatura->save();

                DB::commit();
                return response()->json([
                    "ok" =>true,
                    "data" =>$modelos,
                    "exitoso" =>'Se guardo satisfactoriamente'
                ]);
          }
           

          
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                "ok" =>false,
                "data" =>$th->getMessage(),
                "error"=>'Hubo un error consulte con el Administrador del sistema'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Modelo $modelo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modelo $modelo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Modelo $modelo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Modelo $modelo)
    {
        //
    }
}
