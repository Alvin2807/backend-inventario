<?php

namespace App\Http\Controllers\API;

use App\Models\Insumo;
use App\Http\Controllers\Controller;
use App\Models\VistaInsumos;
use Illuminate\Http\Request;
use App\Http\Requests\Insumos\StoreRequest;
use App\Http\Requests\Insumos\EditarRequest;
use App\Models\Nomenclatura;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class InsumosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Mostrar insumos
        $insumo = VistaInsumos::all();
        return response()->json([
            "ok" =>true,
            "data" =>$insumo
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
        //Registrar insumos
        try {
           DB::beginTransaction();
           $codigo = strtoupper($request->input('codigo'));
           $consulta = Insumo::
           select('id_insumo','codigo')
           ->where('codigo', $codigo)
           ->get();
           if (count($consulta) > 0) {
            return response()->json([
                "ok" =>true,
                "existeInsumo" =>'Ya existe un insumo con el código '.$codigo
            ]);
           } else {
            $insumos = new Insumo();
            $insumos->fk_nomenclatura = $request->input('fk_nomenclatura');
            $insumos->referencia = strtoupper($request->input('referencia'));
            $insumos->fk_marca = $request->input('fk_marca');
            $insumos->fk_modelo = $request->input('fk_modelo');
            $insumos->fk_categoria = $request->input('fk_categoria');
            $insumos->fk_color = $request->input('fk_color');
            $insumos->codigo = $codigo;
            $insumos->usuario_crea = strtoupper($request->input('usuario'));
            $insumos->save();
            DB::commit();
            return response()->json([
                "ok" =>true,
                "data"=>$insumos,
                "exitosoInsumo"=>'Se guardo satisfactoriamente'
            ]);
           }

        } catch (\Exception $th) {
           DB::rollBack();
           return response()->json([
            "ok" =>false,
            "data"=>$th->getMessage(),
            "errorInsumo"=>'Hubo un error consulte con el Administrador del sistema'
           ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Insumo $insumo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editarInsumo(EditarRequest $request)
    {
        //Editar Insumo
        try {
            DB::beginTransaction();
            $codigo    = strtoupper($request->input('codigo'));
            $id_insumo = (int)$request->input('id_insumo');
            $consulta  = Insumo::
            select('id_insumo','codigo')
            ->where('codigo', $codigo)
            ->where('id_insumo', '<>', $id_insumo)
            ->get();
            if (count($consulta) > 0) {
                return response()->json([
                    "ok" =>true,
                    "existe" =>'Ya existe un insumo con el código '.$codigo
                ]);
            } else {
                $insumo = new Insumo();
                $data['fk_nomenclatura'] = $request->input('fk_nomenclatura');
                $data['fk_marca']        = $request->input('fk_marca');
                $data['fk_modelo']       = $request->input('fk_modelo');
                $data['fk_categoria']    = $request->input('fk_categoria');
                $data['fk_color']        = $request->input('fk_color');
                $data['referencia']      = strtoupper($request->input('referencia'));
                $data['codigo']          = $codigo;
                $data['usuario_modifica'] = strtoupper($request->input('usuario'));
                $data['fecha_modifica']   = Carbon::now()->format('Y-m-d H:s:i');
                $insumo = Insumo::where('id_insumo', $id_insumo)->update($data);
                DB::commit();
                return response()->json([
                    "ok"=>true,
                    "data"=>$insumo,
                    "exitoso"=>'Se guardo satisfactoriamente'
                ]);
            }
        } catch (\Exception $th) {
           DB::rollBack();
           return response()->json([
            "ok" =>false,
            "data"=>$th->getMessage(),
            "errorInsumoEditar"=>'Hubo un error consulte con el Administrador del sistema'
           ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Insumo $insumo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insumo $insumo)
    {
        //
    }
}
