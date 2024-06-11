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
        $item     = $request->input('modelos');

        for ($i=0; $i <count($item) ; $i++) { 
           $modelo  = strtoupper($item[$i]['modelo']);
           $validar = Modelo::where('modelo', $modelo)->count();
           if ($validar) {
            return [
                "ok" =>true,
                "existeModelo" =>'Ya existe el modelo '.$modelo
            ];
           } else {
            for ($i=0; $i <count($item) ; $i++) { 
                $registrarModelo = new Modelo();
                $registrarModelo->fk_marca = $fk_marca;
                $registrarModelo->modelo = strtoupper($item[$i]['modelo']);
                $registrarModelo->usuario_crea = $usuario;
                $registrarModelo->save();
            }

            DB::commit();
            return response()->json([
                "ok"=>true,
                "data"=>$item,
                "registrarModelo"=>'Se guardo satisfactoriamente'
            ]);
           }
        }
      } catch (\Exception $th) {
        DB::rollBack();
        return response()->json([
            "ok"=>false,
            "data"=>$th->getMessage(),
            "errorModelo"=>'Hubo un error consulte con el Administrador del sistema'
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
