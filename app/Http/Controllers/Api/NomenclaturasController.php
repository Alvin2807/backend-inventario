<?php

namespace App\Http\Controllers\Api;

use App\Models\Nomenclatura;
use App\Http\Controllers\Controller;
use App\Http\Requests\Nomenclaturas\StoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Nomenclaturas\EditarRequest;
use Carbon\Carbon;
class NomenclaturasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Mostrar nomenclaturas
        $nomenclatura = Nomenclatura::all();
        return response()->json([
            "ok" =>true,
            "data" =>$nomenclatura
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
       try {
        DB::beginTransaction();
        $nomenclatura = strtoupper($request->input('nomenclatura'));
        $usuario      = strtoupper($request->input('usuario'));
        $consulta     = Nomenclatura::
        select('id_nomenclatura','nomenclatura')
        ->where('nomenclatura', $nomenclatura)
        ->get();
        if (count($consulta) > 0) {
            return response()->json([
                "ok" =>true,
                "existe"=>'Ya existe una nomenclatura '.$nomenclatura
            ]);
        } else {
            $nomenclaturas = new Nomenclatura();
            $nomenclaturas->nomenclatura = $nomenclatura;
            $nomenclaturas->usuario_crea = $usuario;
            $nomenclaturas->save();

            DB::commit();
            return response()->json([
                "ok" =>true,
                "data"=>$nomenclaturas,
                "exitoso"=>'Se guardo satisfactoriamente'
            ]);
        }

       } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                "ok" =>false,
                "data"=>$th->getMessage(),
                "errorRegistro"=>'Hubo un error consulte con el Administrador del sisetema'
            ]);
       }
       

    }

    /**
     * Display the specified resource.
     */
    public function show(Nomenclatura $nomenclatura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editarNomenclatura(EditarRequest  $request)
    {
        //Editar modelo
        try {
            DB::beginTransaction();
            $nomenclatura    = strtoupper($request->input('nomenclatura'));
            $id_nomenclatura = $request->input('id_nomenclatura');
            $usuario         = strtoupper($request->input('usuario'));
            $consultar       = Nomenclatura::
            select('id_nomenclatura', 'nomenclatura')
            ->where('nomenclatura', $nomenclatura)
            ->get();
            if (count($consultar) > 0) {
               return response()->json([
                "ok" =>true,
                "existeData" =>'Ya existe una nomenclatura '.$nomenclatura
               ]);
            } else {
                $nomenclaturas = new Nomenclatura();
                $data['nomenclatura'] = $nomenclatura;
                $data['usuario_modifica'] = $usuario;
                $data['fecha_modifica']   = Carbon::now()->format('Y-m-d H:i:s');
                $nomenclaturas = Nomenclatura::where('id_nomenclatura', $id_nomenclatura)->update($data);

                DB::commit();
                return response()->json([
                    "ok" =>true,
                    "data"=>$nomenclaturas,
                    "editado"=>'Se guardo satisfactoriamente'
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nomenclatura $nomenclatura)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nomenclatura $nomenclatura)
    {
        //
    }
}
