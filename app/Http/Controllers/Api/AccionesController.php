<?php

namespace App\Http\Controllers\Api;

use App\Models\Accion;
use App\Http\Controllers\Controller;
use App\Models\TipoAccion;
use App\Models\vista_acciones_pendientes;
use App\Models\vista_detalle_acciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Acciones\StoreRequest;
use App\Http\Requests\Acciones\EditarSolicitudRequest;
use App\Models\DetalleAccion;
use App\Models\Producto;
use App\Utils\Utilidades;
use App\Http\Requests\Acciones\CancelarProductoRequest;
use App\Http\Requests\Acciones\CancelarSolicitudRequest;
use Carbon\Carbon;
class AccionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Mostrar las acciones
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function accionesPendientes($id_accion){
        //Mostrar acciones pendientes
        $acciones = vista_acciones_pendientes::all()
        ->where('id_accion', $id_accion)
        ->first();

        $detalles = vista_detalle_acciones::
        select('id_detalle','fk_accion','fk_producto','codigo_producto','categoria','nombre_marca','nombre_modelo','nomenclatura','color','cantidad_solicitada',
        'cantidad_confirmada','cantidad_pendiente','estado','observacion')
        ->where('fk_accion', $id_accion)
        ->get();

        $acciones->detallesAcciones = $detalles;
        return response()->json([
            "ok" =>true,
            "data"=>$acciones
        ]);

        return response()->json([
            "ok" =>true,
            "data"=>$acciones,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        //Registrar entrada
        try {
            DB::beginTransaction();
            $tipo_acciones = TipoAccion::
            select('id_tipo_accion','tipo_accion')
            ->where('tipo_accion', 'ENTRADA')
            ->get();
            if (count($tipo_acciones) > 0) {
                $no_nota = strtoupper($request->input('no_nota'));
                $consulta = Accion::
                select('id_accion','no_nota')
                ->where('no_nota', $no_nota)
                ->get();
                if (count($consulta) > 0) {
                    return response()->json([
                        "existe" => 'Ya existe el número de nota '.$no_nota
                    ]);
                } else {
                    $accion = new Accion();
                    $accion->no_nota = $no_nota;
                    $accion->fecha_nota = Carbon::now();
                    $accion->titulo_nota = strtoupper($request->input('titulo_nota'));
                    $accion->fk_tipo_accion = 1;
                    $accion->fk_despacho_solicitante = $request->input('fk_despacho_solicitante');
                    $accion->fk_despacho_asignado    = $request->input('fk_despacho_asignado');
                    $accion->estado = 'Pendiente';
                    $accion->observacion = ucfirst($request->input('observacion'));
                    $accion->usuario_crea = strtoupper($request->input('usuario'));
                    $accion->save();

                    $items = $request->input('detalle');
                    for ($i=0; $i <count($items) ; $i++) { 
                        $detalleAccion = new DetalleAccion();
                        $detalleAccion->fk_accion = $accion->id;
                        $detalleAccion->fk_producto = $items[$i]['fk_producto'];
                        $detalleAccion->cantidad_solicitada = $items[$i]['cantidad_solicitada'];
                        $detalleAccion->cantidad_confirmada = 0;
                        $detalleAccion->cantidad_pendiente  = $detalleAccion->cantidad_solicitada;
                        $detalleAccion->estado = 'Pendiente';
                        $detalleAccion->observacion = $items[$i]['observacion'];
                        $detalleAccion->usuario_crea =  $accion->usuario_crea;
                        $detalleAccion->save();

                        $actualizarAccion = new Accion();
                        $data['cantidad_solicitada'] = $this->sumarCantidadSolicitada($detalleAccion->fk_accion);
                        $data['cantidad_pendiente']  = $this->sumarCantidadPendiente($detalleAccion->fk_accion);
                        $data['cantidad_confirmada'] = 0;
                        $actualizarAccion = Accion::where('id_accion', $detalleAccion->fk_accion)->update($data);

                        $actualizarProducto = new Producto();
                        $cantidad_solicitada_producto = $this->sumarProductoCantidadSolicitada($items[$i]['fk_producto']);
                        $dataProducto['cantidad_solicitada'] =  $cantidad_solicitada_producto + $items[$i]['cantidad_solicitada'];
                        $dataProducto['fk_ultima_accion']    =  $detalleAccion->fk_accion;
                        $actualizarProducto = Producto::where('id_producto', $items[$i]['fk_producto'])->update($dataProducto);

                    }

                    DB::commit();
                    return response()->json([
                        "ok" =>true,
                        "data" =>$accion,
                        "exitoso" => 'Se guardo satisfactoriamente'
                    ]);
                }
            } else {
                return response()->json([
                    "Error" =>'No se puede generar este proceso de accion'
                ]);
            }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                "ok" =>false,
                "data" =>$th->getMessage(),
                "error" =>'Hubo un error consulte con el Administrador del sistema.'
            ]);
        }
    }

    public function sumarCantidadSolicitada($id_accion){
        $cantidadSolicitada = DetalleAccion::
        select('id_detalle','cantidad_solicitada')
        ->where('fk_accion', $id_accion)
        ->sum('cantidad_solicitada');
        return $cantidadSolicitada;
    }


    public function sumarCantidadPendiente($id_accion){
        $cantidadPendiente = DetalleAccion::
        select('id_detalle','cantidad_accion')
        ->where('fk_accion',$id_accion)
        ->sum('cantidad_pendiente');
        return $cantidadPendiente;
    }

    public function sumarProductoCantidadSolicitada($id_producto) {
        $cantidad_solicitada = Producto::
        select('id_producto','cantidad_solicitada')
        ->where('id_producto', $id_producto)
        ->sum('cantidad_solicitada');
        return $cantidad_solicitada;
    }

   

    /**
     * Display the specified resource.
     */
    public function CancelarSolicitud(CancelarSolicitudRequest $request)
    {
        //Cancelar solicitud
        try {
            DB::beginTransaction();
            $tipo_acciones = $request->input('tipo_accion');
            if ($tipo_acciones === 'ENTRADA' || $tipo_acciones === 'SALIDA') {
                $id_accion = $request->input('id_accion');
                $accion = new Accion();
                $dataAccion['estado']  = 'Cancelado';
                $dataAccion['usuario_modifica'] = strtoupper($request->input('usuario'));
                $dataAccion['fecha_modifica'] = Carbon::now();
                $accion = Accion::where('id_accion', $id_accion)->update($dataAccion);

                $items = $request->input('detalle');
                for ($i=0; $i <count($items) ; $i++) { 
                    if (isset($items[$i]['id_detalle'])) {
                       $detalleAcciones = new DetalleAccion;
                       $detalles['estado'] =  $dataAccion['estado'];
                       $detalles['usuario_modifica'] =  $dataAccion['usuario_modifica'];
                       $detalles['fecha_modifica']   =  $dataAccion['fecha_modifica'];
                       $detalleAcciones = DetalleAccion::where('fk_accion', $id_accion)->update($detalles);

                       $productos = new Producto;
                       $cantidad_solicitada_producto = $this->sumarProductoCantidadSolicitada($items[$i]['fk_producto']);
                       $dataProductos['usuario_modifica']  =   $dataAccion['usuario_modifica'];
                       $dataProductos['fecha_modifica']    =   $dataAccion['fecha_modifica'];
                       $dataProductos['cantidad_solicitada'] = $cantidad_solicitada_producto - $items[$i]['cantidad_solicitada'];
                       $productos = Producto::where('fk_ultima_accion', $id_accion)->update($dataProductos);

                       DB::commit();
                       return response()->json([
                        "ok" =>true,
                        "data"=>$accion,
                        "cancelarExitoso"=>'Se canceló satisfactoriamente'
                       ]);
                    }
                }
            } else {
                return response()->json([
                    "ok"=>true,
                    "mensajeVerificar" =>'No se reconoce este tipo de accion'
                ]);
            }
           
            
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                "ok" =>false,
                "data"=>$th->getMessage(),
                "errorCancelar" =>'Hubo un error consulte con el Administrador del sistema'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editarSolicitud(EditarSolicitudRequest $request)
    {
        //Editar solicitud
        try {
            DB::beginTransaction();
            $id_accion = $request->input('id_accion');
            $validar = Accion::
            where('id_accion', $id_accion)
            ->where('estado', 'Pendiente')
            ->count();
            if ($validar) {
                $data['no_nota'] = strtoupper($request->input('no_nota'));
                $data['titulo_nota'] = strtoupper($request->input('titulo_nota'));
                $data['fk_despacho_asignado'] = $request->input('fk_despacho_asignado');
                $data['observacion'] = ucfirst($request->input('observacion'));
                $data['usuario_modifica'] = strtoupper($request->input('usuario'));
                $data['fecha_modifica'] = Carbon::now();
                $accion = Accion::where('id_accion', $id_accion)->update($data);

                $items = $request->input('detalle');
                for ($i=0; $i <count($items) ; $i++) { 
                   if (isset($items[$i]['id_detalle'])) {
                    $detalles = new DetalleAccion();
                    $detallesAccion['cantidad_solicitada'] = $items[$i]['cantidad_solicitada'];
                    $detallesAccion['cantidad_pendiente']  = $items[$i]['cantidad_solicitada'];
                    $detallesAccion['cantidad_confirmada']  = $detallesAccion['cantidad_solicitada'] - $detallesAccion['cantidad_pendiente'];
                    $detallesAccion['observacion']          = ucfirst($items[$i]['observacion']);
                    $detallesAccion['usuario_modifica'] =  $data['usuario_modifica'];
                    $detallesAccion['fecha_modifica'] = $data['fecha_modifica'];
                    $detalles = DetalleAccion::where('id_detalle', $items[$i]['id_detalle'])->update($detallesAccion);

                    $actualizarAccion = new Accion();
                    $dataAccion['cantidad_solicitada'] = $this->sumarCantidadSolicitada($id_accion);
                    $dataAccion['cantidad_pendiente']  = $this->sumarCantidadPendiente($id_accion);
                    $dataAccion['cantidad_confirmada'] = 0;
                    $actualizarAccion = Accion::where('id_accion', $id_accion)->update($dataAccion);

                    $actualizarProducto = new Producto();
                    $dataProducto['cantidad_solicitada'] =  $items[$i]['cantidad_solicitada_productos'] - $items[$i]['cantidad_solicitada_detalle'] + $items[$i]['cantidad_solicitada'];
                    $dataProducto['usuario_modifica']    = $data['usuario_modifica'];
                    $dataProducto['fecha_modifica']      = $data['fecha_modifica'];
                    $actualizarProducto = Producto::where('id_producto', $items[$i]['fk_producto'])->update($dataProducto);


                   } else {
                    $detalleRegistrarAccion = new DetalleAccion();
                    $detalleRegistrarAccion->fk_accion = $id_accion;
                    $detalleRegistrarAccion->fk_producto = $items[$i]['fk_producto'];
                    $detalleRegistrarAccion->cantidad_solicitada = $items[$i]['cantidad_solicitada'];
                    $detalleRegistrarAccion->cantidad_pendiente  = $items[$i]['cantidad_solicitada'];
                    $detalleRegistrarAccion->cantidad_confirmada = $detalleRegistrarAccion->cantidad_solicitada - $detalleRegistrarAccion->cantidad_pendiente;
                    $detalleRegistrarAccion->cantidad_pendiente  = $detalleRegistrarAccion->cantidad_solicitada;
                    $detalleRegistrarAccion->estado = 'Pendiente';
                    $detalleRegistrarAccion->observacion = ucfirst($items[$i]['observacion']);
                    $detalleRegistrarAccion->usuario_crea =  $data['usuario_modifica'];
                    $detalleRegistrarAccion->save();

                    $actualizarAccion = new Accion();
                    $dataAccionRegistrar['cantidad_solicitada'] = $this->sumarCantidadSolicitada($id_accion);
                    $dataAccionRegistrar['cantidad_pendiente']  = $this->sumarCantidadPendiente($id_accion);
                    $dataAccionRegistrar['cantidad_confirmada'] = 0;
                    $dataAccionRegistrar['usuario_modifica'] = $data['usuario_modifica'];
                    $detallesRegistrar['fecha_modifica']     = $data['fecha_modifica'];
                    $actualizarAccion = Accion::where('id_accion', $id_accion)->update($dataAccionRegistrar);

                    $actualizarProducto = new Producto();
                    $cantidad_solicitada_producto = $this->sumarProductoCantidadSolicitada($items[$i]['fk_producto']);
                    $dataProducto['cantidad_solicitada'] =  $cantidad_solicitada_producto + $items[$i]['cantidad_solicitada'];
                    $dataProducto['usuario_modifica']    = $data['usuario_modifica'];
                    $dataProducto['fecha_modifica']      = $data['fecha_modifica'];
                    $actualizarProducto = Producto::where('id_producto', $items[$i]['fk_producto'])->update($dataProducto);


                   }
                }

                DB::commit();
                return response()->json([
                    "ok" =>true,
                    "data" =>$accion,
                    "exitoso" =>'Se guardo satisfactoriamente'
                ]);
            }
        } catch (\Exception $th) {
           DB::rollBack();
           return response()->json([
            "ok" =>false,
            "data" =>$th->getMessage(),
            "error" =>'Hubo un error consulte con el Administrador del sistema'
           ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function cancelarProductoSolicitud(CancelarProductoRequest $request, Accion $accion)
    {
        //Cancelar un producto de una solicitud
        try {
           DB::beginTransaction();
           $id_detalle = $request->input('id_detalle');
           $fk_producto = $request->input('fk_producto');
           $cantidad_solicitada_productos = $request->input('cantidad_solicitada_productos');
           $cantidad_solicitada_detalle = $request->input('cantidad_solicitada');
           $usuario = strtoupper($request->input('usuario'));

           $detalleAccion = DetalleAccion::
           select('id_detalle','fk_producto','cantidad_confirmada')
           ->where('id_detalle', $id_detalle)
           ->where('fk_producto', $fk_producto)
           ->where('cantidad_confirmada', '>', 0)
           ->count();

           if ($detalleAccion) {
            return response()->json([
                "ok" =>true,
                "data"=>$detalleAccion,
                "mensajeNoCancelado" =>'No se puede cancelar este producto, porque tiene cantidad confirmada.'
            ]);
           } else {
            $actualizar = new DetalleAccion();
            $data['estado'] = 'Cancelado';
            $data['usuario_modifica'] = $usuario;
            $data['fecha_modifica'] = Carbon::now();
            $actualzar = DetalleAccion::where('id_detalle',$id_detalle)->update($data);

            $actualizarProducto = new Producto();
            $dataProducto['cantidad_solicitada'] =  $cantidad_solicitada_productos - $cantidad_solicitada_detalle;
            $dataProducto['usuario_modifica']    =  $usuario;
            $dataProducto['fecha_modifica']      =  $data['fecha_modifica'];
            $actualizarProducto = Producto::where('id_producto', $fk_producto)->update($dataProducto);

            DB::commit();
            return response()->json([
                "ok" =>true,
                "data"=>$actualizar,
                'exitosoCancelado' =>'Se canceló satisfactoriamente'
            ]);
           }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                "ok" =>false,
                "data"=>$th->getMessage(),
                "error"=>'Hubo un error consulte con el Administrador del sistema'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accion $accion)
    {
        //
    }
}
