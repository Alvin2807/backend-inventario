<?php

namespace App\Http\Controllers\Api;

use App\Models\Acciones;
use App\Http\Controllers\Controller;
use App\Models\VistaAciones;
use Illuminate\Http\Request;
use App\Http\Requests\Acciones\StoreRequest;
use App\Models\DetalleAccion;
use App\Models\TipoAccion;
use Carbon\Carbon;
use App\Http\Requests\Acciones\NotaExiste;
use App\Http\Requests\Acciones\DetalleRequestMostrar;
use App\Models\VistaDetalleAcciones;
use Illuminate\Support\Facades\DB;

class AccionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Mostrar acciones pendientes
        $acciones = VistaAciones::
        select('id_accion','registrado_por','no_nota','fecha_nota','fecha_confirmacion','titulo_nota','no_incidencia','observacion',
        'fk_tipo_accion','fk_despacho','estado','despacho','tipo_accion','registrado_por',
        'cantidad_solicitada','cantidad_confirmada','cantidad_pendiente')
        ->where('estado','Pendiente')
        ->orderBy('id_accion', 'desc')
        ->get();
        return response()->json([
            "ok" =>true,
            "data"=>$acciones,
            "Pendientes" =>count($acciones)
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
        //Registrar acciones
        try {
            DB::beginTransaction();
            $tipo_accion = strtoupper($request->input('tipo_accion'));
            if ($tipo_accion == 'ENTRADA') {
              $no_nota     = strtoupper($request->input('no_nota'));
              $fecha_nota  = Carbon::now()->format('Y-m-d');
              $titulo_nota = ucwords($request->input('titulo_nota'));
              $fk_tipo_accion = $request->input('fk_tipo_accion');
              $fk_despacho = $request->input('fk_despacho');
              $registrado_por = strtoupper($request->input('registrado_por'));
              $observacion = ucfirst($request->input('observacion'));
              $usuario = strtoupper($request->input('usuario'));

              $acciones = new Acciones();
              $acciones->no_nota        = $no_nota;
              $acciones->fecha_nota     = $fecha_nota;
              $acciones->titulo_nota    = $titulo_nota;
              $acciones->fk_tipo_accion = $fk_tipo_accion;
              $acciones->usuario_crea = $usuario;
              $acciones->fk_despacho = $fk_despacho;
              $acciones->registrado_por = $registrado_por;
              $acciones->observacion = $observacion;
              $acciones->save();

              $items = $request->input('detalles');
              for ($i=0; $i <count($items) ; $i++) { 
                $detalle = new DetalleAccion();
                $detalle->fk_accion = $acciones->id;
                $detalle->fk_insumo = $items[$i]['fk_insumo'];
                $detalle->fk_tipo_accion = $acciones->fk_tipo_accion;
                $detalle->cantidad_solicitada = $items[$i]['cantidad_solicitada'];
                $detalle->cantidad_confirmada = 0;
                //$detalle->observacion = $items[$i]['observacion'];
                $detalle->cantidad_pendiente  = $detalle->cantidad_solicitada - $detalle->cantidad_confirmada;
                $detalle->usuario_crea = $acciones->usuario_crea;
                $detalle->save();

                $dataAccionCantidad = new Acciones();
                $data['cantidad_solicitada'] = $this->sumarCantidadSolicitada($acciones->id);
                $data['cantidad_confirmada'] = $this->sumarCantidadConfirmada($acciones->id);
                $data['cantidad_pendiente']  = $this->sumarCantidadPendiente($acciones->id);
                $dataAccionCantidad = Acciones::where('id_accion', $acciones->id)->update($data);
              }
              DB::commit();
              return response()->json([
                "ok"=>true,
                "data"=>$acciones,
                "exitoso"=>'Se guardo satisfactoriamente'
              ]);
            
            }
         
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                "ok"=>false,
                "data"=>$th->getMessage(),
                "errorRegistro"=>'Hubo un error consulte con el Administrador del sistema'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function sumarCantidadSolicitada($id_accion)
    {
        $cantidad_solicitada = DetalleAccion::
        select('id_detalle','cantidad_solicitada')
        ->where('fk_accion', $id_accion)
        ->sum('cantidad_solicitada');
        return $cantidad_solicitada;
    }

    public function sumarCantidadConfirmada($id_accion)
    {
        $cantidad_confirmada = DetalleAccion::
        select('id_detalle','cantidad_confirmada')
        ->where('fk_accion', $id_accion)
        ->sum('cantidad_confirmada');
        return $cantidad_confirmada;
    }

    public function sumarCantidadPendiente($id_accion)
    {
        $cantidad_pendiente = DetalleAccion::
        select('id_detalle','cantidad_pendiente')
        ->where('fk_accion', $id_accion)
        ->sum('cantidad_pendiente');
        return $cantidad_pendiente;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function mostrarNotaExiste(NotaExiste $request)
    {
        try {
            DB::beginTransaction();
            $no_nota    = strtoupper($request->input('no_nota'));
            $id_accion  = $request->input('id_accion');
            $acciones   = Acciones::
            select('id_accion', 'no_nota')
            ->where('no_nota', $no_nota)
            ->where('id_accion', '<>', $id_accion)
            ->count();
            DB::commit();
            return response([
                "ok" =>true,
                "data" =>$acciones
            ]);
        } catch (\Exception $th) {
            DB::rollBack();
            return response([
                "ok" =>false,
                "data"=>$th->getMessage(),
                "errorNota" =>'Hubo un error consulte con el Administrador del Sistema'
            ]);
        }
    }

    public function mostrarContadorNota(){
        $acciones = VistaAciones::
        select('id_accion', 'tipo_accion')
        ->where('tipo_accion', 'ENTRADA')
        ->count();
        return response()->json([
            "ok" =>true,
            "data"=>$acciones,
           
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function accionesPendientes($id_accion)
    {
        //Mostrar el detalle de la cciones pendientes
        $acciones = VistaAciones::all()
        ->where('estado', 'Pendiente')
        ->where('id_accion', $id_accion)
        ->first();
        $detallesAcciones = VistaDetalleAcciones::
        select('id_detalle','fk_accion','tipo_accion','codigo','marca','modelo','categoria','nomenclatura','color','cantidad_solicitada',
        'cantidad_confirmada','cantidad_pendiente','estado','observacion','registrado_por')
        ->where('estado','Pendiente')
        ->where('fk_accion', $id_accion)
        ->get();

        $acciones->Detalles = $detallesAcciones;
        return response()->json([
            "ok" =>true,
            "data"=>$acciones
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Acciones $acciones)
    {
        //
    }
}
