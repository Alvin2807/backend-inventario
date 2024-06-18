<?php

namespace App\Http\Controllers\Api;

use App\Models\Modelo;
use App\Http\Controllers\Controller;
use App\Models\VistaModeloMarca;
use Illuminate\Http\Request;
use App\Http\Requests\Modelos\StoreRequest;
use App\Models\Nomenclatura;
use App\Models\VistaMarcaModelo;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Modelos\EditarRequest;
use Carbon\Carbon;

class ModelosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Mostrar modelos
        $modelo = VistaMarcaModelo::all();
        return response()->json([
            "ok"=>true,
            "data"=>$modelo
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
    public function editarModelo(EditarRequest $request)
    {
        //Editar modelo
        try {
            DB::beginTransaction();
            $nombre_modelo = strtoupper($request->input('modelo'));
            $id_modelo     = $request->input('id_modelo');
            $usuario       = strtoupper($request->input('usuario'));
            $consulta      = Modelo::
            select('id_modelo','modelo')
            ->where('modelo', $nombre_modelo)
            ->where('id_modelo', '<>',$id_modelo)
            ->get();
            if (count($consulta) > 0) {
               return response()->json([
                "ok" =>true,
                "existe"=>'Ya existe un modelo '.$nombre_modelo
               ]);
            } else {
                $modelosEditar = new Modelo();
                $modelos['modelo'] = $nombre_modelo;
                $modelos['usuario_modifica'] = $usuario;
                $modelos['fecha_modifica']   = Carbon::now()->format('Y-m-d H:i:s');
                $modelosEditar = Modelo::where('id_modelo',$id_modelo)->update($modelos);
                DB::commit();
                return response()->json([
                    "ok"=>true,
                    "data"=>$modelos,
                    "exitoso"=>'Se guardo satisfactoriamente'
                ]);
            }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                "ok"=>false,
                "data"=>$th->getMessage(),
                "errorModificado"=>'Hubo un error consulte con el Administrador del sistema'
            ]);
        }
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
