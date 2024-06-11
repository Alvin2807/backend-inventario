<?php

namespace App\Http\Controllers\Api;

use App\Models\Marca;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Marcas\StoreRequest;
use App\Models\VistaMarcaModelo;
use App\Http\Requests\Marcas\EditarRequest;
use Carbon\Carbon;
class MarcasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Mostrar las marcas
        $marca = Marca::
        select('id_marca','marca')
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
            $marca = strtoupper($request->input('marca'));
            $consulta = Marca::
            select('id_marca','marca')
            ->where('marca', $marca)
            ->get();

            if (count($consulta) > 0) {
                return response()->json([
                 "ok" =>true,
                 "existe"=>'Ya existe la marca '.$marca
                ]);
             } else {
                 $marcas = new Marca();
                 $marcas->marca = strtoupper($request->input('marca'));
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
                "errorRegistro" =>'Hubo un error consulte con el Administrador del sistema'
            ]);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function editarMarca(EditarRequest $request)
    {
        //Editar una marca
        DB::beginTransaction();
        try {
            $marca    = strtoupper($request->input('marca'));
            $id_marca = (int)$request->input('id_marca');
            $usuario      = strtoupper($request->input('usuario'));
            $consulta     = Marca::
            select('id_marca','marca')
            ->where('marca', $marca)
            ->where('id_marca','<>', $id_marca)
            ->get();
            if (count($consulta) > 0) {
                return response()->json([
                    "ok" =>true,
                    "existeMarca" =>'Ya existe una marca '.$marca
                ]);
            } else {
                $marcas = new Marca();
                $data['marca'] = $marca;
                $data['usuario_modifica'] = $usuario;
                $data['fecha_modifica']   = Carbon::now()->format('d-m-y H:i:s');
                $marcas = Marca::where('id_marca', $id_marca)->update($data);
                DB::commit();
                return response()->json([
                    "ok" =>true,
                    "data"=>$marcas,
                    "modificado"=>'Se guardo satisfactoriamente'
                ]);
            }

            
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                "ok" =>false,
                "data"=>$th->getMessage(),
                "errorModifica" =>'Hubo un error consulte con el Administrador del sistema'
            ]);
        }
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
