<?php

namespace App\Http\Controllers\Api;

use App\Models\Marca;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Marcas\StoreRequest;

class MarcasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Mostrar las marcas
        $marca = Marca::
        select('id_marca','nombre_marca')
        ->get();
        return response()->json([
            "ok" =>true,
            "data"=>$marca
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
        //Registrar marca

        try {
            DB::beginTransaction();
            $nombre_marca = strtoupper($request->input('nombre_marca'));
            $consulta = Marca::
            select('id_marca','nombre_marca')
            ->where('nombre_marca', $nombre_marca)
            ->get();

            if (count($consulta) > 0) {
                return response()->json([
                 "ok" =>true,
                 "existe"=>'Ya existe la marca '.$nombre_marca
                ]);
             } else {
                 $marcas = new Marca();
                 $marcas->nombre_marca = strtoupper($request->input('nombre_marca'));
                 $marcas->usuario_crea = strtoupper($request->input('usuario'));
                 $marcas->save();
                 DB::commit();
                 return response()->json([
                    "ok" =>true,
                    "data"=>$marcas,
                    "exitoso" =>'Se guardo satisfactoriamente'
                 ]);
             }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                "ok" =>false,
                "data"=>$th->getMessage(),
                "error" =>'Hubo un error consulte con el Administrador del sistema'
            ]);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Marca $marca)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marca $marca)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marca $marca)
    {
        //
    }
}
