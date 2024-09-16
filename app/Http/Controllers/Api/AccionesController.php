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
use App\Http\Requests\Acciones\EditarAccionRequest;
use App\Models\Insumo;
use App\Models\VistaDetalleAcciones;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Acciones\CancelarInsumoDetalleRequest;
use App\Http\Requests\Acciones\CancelarRequest;
use App\Http\Requests\Acciones\ConfirmarSolicitudRequest;
use App\Models\Deposito;
use App\Models\Ubicacion;

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
                $detalle->no_item   = $items[$i]['no_item'];
                $detalle->fk_tipo_accion = $acciones->fk_tipo_accion;
                $detalle->registrado_por = $registrado_por;
                $detalle->cantidad_solicitada = $items[$i]['cantidad_solicitada'];
                $detalle->cantidad_confirmada = 0;
                $detalle->cantidad_pendiente  = $detalle->cantidad_solicitada - $detalle->cantidad_confirmada;
                $detalle->usuario_crea = $acciones->usuario_crea;
                $detalle->save();

                $dataAccionCantidad = new Acciones();
                $data['cantidad_solicitada'] = $this->sumarCantidadSolicitada($acciones->id);
                $data['cantidad_confirmada'] = $this->sumarCantidadConfirmada($acciones->id);
                $data['cantidad_pendiente']  = $this->sumarCantidadPendiente($acciones->id);
                $dataAccionCantidad = Acciones::where('id_accion', $acciones->id)->update($data);

                $consultarInsumo = Insumo::
                select('id_insumo','cantidad_pedida')
                ->where('id_insumo', $items[$i]['fk_insumo'])
                ->get();
                if (count($consultarInsumo) > 0) {
                    $actualizarInsumo = new Insumo();
                    $dataInsumo['cantidad_pedida'] = $consultarInsumo[0]['cantidad_pedida'] + $items[$i]['cantidad_solicitada'];
                    $actualizarInsumo = Insumo::where('id_insumo', $items[$i]['fk_insumo'])->update($dataInsumo);
                }
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

    public function sumarCantidadCancelada($id_accion)
    {
        $cantidad_pendiente = DetalleAccion::
        select('id_detalle','cantidad_solicitada')
        ->where('fk_accion', $id_accion)
        ->where('estado','cancelado')
        ->sum('cantidad_solicitada');
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
        'cantidad_confirmada','cantidad_pendiente','estado','observacion','registrado_por','referencia','no_item','fk_insumo')
        ->where('estado','Pendiente')
        ->where('fk_accion', $id_accion)
        ->get();

        $acciones->detalles = $detallesAcciones;
        return response()->json([
            "ok" =>true,
            "data"=>$acciones
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function editarAccion(EditarAccionRequest $request)
    {
        //Editar Acciones
        try {
            DB::beginTransaction();
            $id_accion = $request->input('fk_accion');
            $fk_tipo_accion = $request->input('fk_tipo_accion');
            $validar  = Acciones::
            where('id_accion', $id_accion)
            ->where('estado', 'Pendiente')
            ->count();
            if ($validar) {
               $data['fk_despacho'] = $request->input('fk_despacho');
               $data['titulo_nota'] = ucwords($request->input('titulo_nota'));
               $data['fecha_nota']  = Carbon::now()->format('Y-m-d');
               $data['observacion'] = ucfirst($request->input('observacion'));
               $data['usuario_modifica'] = strtoupper($request->input('usuario'));
               $data['fecha_modifica']   = Carbon::now()->format('Y-m-d H:i:s');
               $data["registrado_por"]   = strtoupper($request->input('registrado_por'));
               $acciones = Acciones::where('id_accion', $id_accion)->update($data);

               $items = $request->input('detalles');
               for ($i=0; $i <count($items) ; $i++) { 
                if (isset($items[$i]['id_detalle'])) {
                   $detallesAccion = new DetalleAccion();
                   $detalleData['fk_insumo'] = $items[$i]['fk_insumo'];
                   $detalleData['cantidad_solicitada'] = $items[$i]['cantidad_solicitada'];
                   $detalleData['cantidad_confirmada'] = 0;
                   $detalleData['cantidad_pendiente']  = $detalleData['cantidad_solicitada'] -  $detalleData['cantidad_confirmada'];
                   $detalleData['usuario_modifica']    = $data['usuario_modifica'];
                   $detalleData['fecha_modifica']      = $data['fecha_modifica'];
                   $detallesAccion = DetalleAccion::where('id_detalle', $items[$i]['id_detalle'])->update($detalleData);

                   $dataAccionCantidad = new Acciones();
                   $dataAccion['cantidad_solicitada'] = $this->sumarCantidadSolicitada($id_accion);
                   $dataAccion['cantidad_confirmada'] = $this->sumarCantidadConfirmada($id_accion);
                   $dataAccion['cantidad_pendiente']  = $this->sumarCantidadPendiente($id_accion);
                   $dataAccionCantidad = Acciones::where('id_accion', $id_accion)->update($dataAccion);
                } else {
                    $detalleNuevo = new DetalleAccion();
                    $detalleNuevo->no_item = $items[$i]['no_item'];
                    $detalleNuevo->fk_tipo_accion = $fk_tipo_accion;
                    $detalleNuevo->fk_accion = $id_accion;
                    $detalleNuevo->fk_insumo = $items[$i]['fk_insumo'];
                    $detalleNuevo->cantidad_solicitada = $items[$i]['cantidad_solicitada'];
                    $detalleNuevo->cantidad_confirmada = 0;
                    $detalleNuevo->cantidad_pendiente =   $detalleNuevo->cantidad_solicitada - $detalleNuevo->cantidad_confirmada;
                    $detalleNuevo->registrado_por =  $data["registrado_por"];
                    $detalleNuevo->usuario_crea =  $data['usuario_modifica'];
                    $detalleNuevo->save();

                    $dataAcciones= new Acciones();
                    $dataAccion['cantidad_solicitada'] = $this->sumarCantidadSolicitada($id_accion);
                    $dataAccion['cantidad_confirmada'] = $this->sumarCantidadConfirmada($id_accion);
                    $dataAccion['cantidad_pendiente']  = $this->sumarCantidadPendiente($id_accion);
                    $dataAcciones = Acciones::where('id_accion', $id_accion)->update($dataAccion);

                    $consultarInsumo = Insumo::
                    select('id_insumo','cantidad_pedida')
                    ->where('id_insumo', $items[$i]['fk_insumo'])
                    ->get();
                    if (count($consultarInsumo) > 0) {
                        $actualizarInsumo = new Insumo();
                        $dataInsumo['cantidad_pedida'] = $consultarInsumo[0]['cantidad_pedida'] + $items[$i]['cantidad_solicitada'];
                        $actualizarInsumo = Insumo::where('id_insumo', $items[$i]['fk_insumo'])->update($dataInsumo);
                    }
                    }
               }
               DB::commit();
               return response()->json([
                "data" =>true,
                "ok" =>$acciones,
                "exitoso" =>'Se guardo satisfactoriamente'
               ]);
            } else {
                return 'No se puede editar esta solicitud';
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

    public function traerUltimoRegistro(){
        $acciones =  Acciones::
        select('id_accion')
        ->orderBy('id_accion', 'desc')
        ->first();
        return response()->json([
            "ok"=>true,
            "data"=>$acciones
        ]);
    }

    public function cancelarInsumo(CancelarInsumoDetalleRequest $request){
        DB::beginTransaction();
        try {
            $id_detalle = $request->input('id_detalle');
            $fk_insumo  = $request->input('fk_insumo');
            $usuario    = strtoupper($request->input('usuario'));
            $id_accion  = $request->input('id_accion');
            $cantidad_solicitada = $request->input('cantidad_solicitada');
            $consulta   = DetalleAccion::
            select('id_detalle','fk_insumo', 'cantidad_solicitada')
            ->where('id_detalle', $id_detalle)
            ->where('fk_insumo', $fk_insumo)
            ->where('cantidad_confirmada', '>', 0)
            ->get();
            if (count($consulta) > 0) {
                return 'No se puede cancelar este artículo';
            } else {
                $detalleAcciones = DetalleAccion::where('id_detalle', $id_detalle)->delete();
                $dataAccionCantidad = new Acciones();
                $dataAccion['usuario_modifica'] = $usuario;
                $dataAccion['cantidad_solicitada'] = $this->sumarCantidadSolicitada($id_accion);
                $dataAccion['cantidad_confirmada'] = $this->sumarCantidadConfirmada($id_accion);
                $dataAccion['cantidad_pendiente']  = $this->sumarCantidadPendiente($id_accion);
                $dataAccionCantidad = Acciones::where('id_accion', $id_accion)->update($dataAccion);

                $consultarInsumo = Insumo::
                select('id_insumo','cantidad_pedida')
                ->where('id_insumo', $fk_insumo)
                ->get();
                if (count($consultarInsumo) > 0) {
                    $actualizarInsumo = new Insumo();
                    $dataInsumo['cantidad_pedida'] = $consultarInsumo[0]['cantidad_pedida'] - $cantidad_solicitada;
                    $actualizarInsumo = Insumo::where('id_insumo', $fk_insumo)->update($dataInsumo);
                }

                DB::commit();
                return response()->json([
                    "ok" =>true,
                    "data"=>$detalleAcciones,
                    "canceladoInsumo" =>'Se canceló satisfactoriamente'

                ]);
            }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                "ok" =>false,
                "data"=>$th->getMessage(),
                "errorCanceladoInsumo" =>'Hubo un error consulte con el Administrador del sistema'
            ]);
        }
    }

    public function cancelarAccion(CancelarRequest $request){
        try {
           DB::beginTransaction();
           $id_accion = $request->input('id_accion');
           $usuario   = strtoupper($request->input('usuario'));
           $consulta  = Acciones::
           select('id_accion')
           ->where('id_accion', $id_accion)
           ->where('estado', 'Pendiente')
           ->get();
           if (count($consulta) > 0) {
            $acciones = new Acciones();
            $accionesData['estado'] = 'Cancelado';
            $accionesData['fecha_modifica'] = Carbon::now()->format('Y-m-d H:i:s');
            $accionesData['usuario_modifica'] = $usuario;
            $acciones = Acciones::where('id_accion', $id_accion)->update($accionesData);

            $items = $request->input('detalles');
            for ($i=0; $i <count($items) ; $i++) { 
                if (isset($items[$i]['id_detalle'])) {
                    $detalleAccion = new DetalleAccion();
                    $detalleData['estado'] =  $accionesData['estado'];
                    $detalleData['usuario_modifica'] = $accionesData['usuario_modifica'];
                    $detalleData['fecha_modifica'] =  $accionesData['fecha_modifica'];
                    $detalleAccion = DetalleAccion::where('id_detalle', $items[$i]['id_detalle'])->update($detalleData);

                    $consultarInsumo = Insumo::
                    select('id_insumo','cantidad_pedida')
                    ->where('id_insumo', $items[$i]['fk_insumo'])
                    ->get();
                    if (count($consultarInsumo) > 0) {
                        $actualizarInsumo = new Insumo();
                        $dataInsumo['cantidad_pedida'] = $consultarInsumo[0]['cantidad_pedida'] - $items[$i]['cantidad_solicitada'];
                        $actualizarInsumo = Insumo::where('id_insumo', $items[$i]['fk_insumo'])->update($dataInsumo);
                    }
                }
            }
            DB::commit();
            return response()->json([
                "ok" =>true,
                "data"=>$acciones,
                "exitosoCancelado" =>'Se canceló satisfactoriamente'
            ]);
            
           } else {
            return 'No se puede cancelar esta acción';
           }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                "ok" =>false,
                "data"=>$th->getMessage(),
                "errorCancelado"=>'Hubo un error consulte con el Administrador del sistema'
            ]);
        }
    }

    public function cofirmarSolicitud(ConfirmarSolicitudRequest $request){
        try {
            DB::beginTransaction();
            $id_accion = $request->input('id_accion');
            $consulta  = Acciones::
            where('id_accion', $id_accion)
            ->where('estado', 'Pendiente')
            ->get();
        
            if (count($consulta) > 0) {
                $acciones = new Acciones();
                $data['fk_despacho'] = $request->input('fk_despacho');
                $data['titulo_nota'] = ucwords($request->input('titulo_nota'));
                $data['fecha_nota']  = Carbon::now()->format('Y-m-d');
                $data['observacion'] = ucfirst($request->input('observacion'));
                $data['usuario_modifica'] = strtoupper($request->input('usuario'));
                $data['fecha_modifica']   = Carbon::now()->format('Y-m-d H:i:s');
                $data["registrado_por"]   = strtoupper($request->input('registrado_por'));
                $acciones = Acciones::where('id_accion', $id_accion)->update($data);

                $items = $request->input('detalles');
                for ($i=0; $i <count($items) ; $i++) { 
                    $consultaDetalle = DetalleAccion::
                    select('id_detalle','cantidad_solicitada','cantidad_confirmada','cantidad_pendiente','fk_insumo')
                    ->where('id_detalle', $items[$i]['id_detalle'])
                    ->where('fk_accion', $id_accion)
                    ->where('estado', 'Pendiente')
                    ->get();
                    if (count($consultaDetalle) > 0) {
                        if (isset($items[$i]['id_detalle'])) {
                            $detallesAccion = new DetalleAccion();
                            $dataDetalle['cantidad_solicitada'] =  $items[$i]['cantidad_solicitada'];
                            $dataDetalle['cantidad_confirmada'] =  $consultaDetalle[0]['cantidad_confirmada'] + $items[$i]['cantidad_solicitada'];
                            $dataDetalle['cantidad_pendiente']  =  $items[$i]['cantidad_solicitada'] - $dataDetalle['cantidad_confirmada'];
                            $dataDetalle['usuario_modifica']    =  $data['usuario_modifica'];
                            $dataDetalle['fecha_modifica']      =  $data['fecha_modifica'];
                            $detallesAccion = DetalleAccion::where('id_detalle', $items[$i]['id_detalle'])->update($dataDetalle);

                            $consultarInsumo = Insumo::
                            select('id_insumo','cantidad_pedida')
                            ->where('id_insumo', $items[$i]['fk_insumo'])
                            ->get();
                            if (count($consultarInsumo) > 0) {
                                $actualizarInsumo = new Insumo();
                                $valorA = $consultarInsumo[0]['cantidad_pedida'] - $dataDetalle['cantidad_confirmada'];
                                $valorB = $valorA - $valorA;
                                $dataInsumo['cantidad_pedida']  = $valorB;
                                $dataInsumo['estado'] = 'Disponible';
                                $dataInsumo['usuario_modifica'] =  $data['usuario_modifica'];
                                $dataInsumo['fecha_modifica']   =  $data['fecha_modifica'];
                                $dataInsumo['stock'] =  $consultarInsumo[0]['stock'] + $items[$i]['cantidad_solicitada'];
                                $actualizarInsumo = Insumo::where('id_insumo', $items[$i]['fk_insumo'])->update($dataInsumo);

                                $consultaDeposito = Deposito::
                                select('id_deposito','estado')
                                ->where('estado', 'P')
                                ->get();
                                if (count($consultaDeposito) > 0) {
                                    $consultaUbicacion = Ubicacion::
                                    select('id_ubicacion','stock','fk_deposito','fk_insumo')
                                    ->where('fk_insumo', $items[$i]['fk_insumo'])
                                    ->where('fk_deposito', $consultaDeposito[0]['id_deposito'])
                                    ->get();
                                    if (count($consultaUbicacion) > 0) {
                                        $actualizarUbicacion = new Ubicacion();
                                        $dataUbicacion['fk_insumo']   =  $items[$i]['fk_insumo'];
                                        $dataUbicacion['fk_deposito'] =  $consultaDeposito[0]['id_deposito'];
                                        $dataUbicacion['stock']       = $consultaUbicacion[0]['stock'] + $items[$i]['cantidad_solicitada'];
                                        $dataUbicacion['usuario_modifica'] =  $data['usuario_modifica'];
                                        $dataUbicacion['fecha_modifica']   =  $data['fecha_modifica'];
                                        $actualizarUbicacion = Ubicacion::where('id_ubicacion', $consultaUbicacion[0]['id_ubicacion'])->update($dataUbicacion);
                                    } else {
                                        $registrarUbicacion = new Ubicacion();
                                        $registrarUbicacion->fk_insumo   = $items[$i]['fk_insumo'];
                                        $registrarUbicacion->fk_deposito = $consultaDeposito[0]['id_deposito'];
                                        $registrarUbicacion->stock = $items[$i]['cantidad_solicitada'];
                                        $registrarUbicacion->usuario_crea = $data['usuario_modifica'];
                                        $registrarUbicacion->save();
                                    }
                                }

                               

                            }
                        }
                
                    }

                    $consultaEstadoDetalle = DetalleAccion::
                    select('id_detalle','cantidad_confirmada','cantidad_solicitada')
                    ->where('id_detalle', $items[$i]['id_detalle'])
                    ->where('estado','Pendiente')
                    ->get();
                    if (count($consultaDetalle) > 0) {
                        $actualizarDetalles = new DetalleAccion();
                        if ($consultaEstadoDetalle[0]['cantidad_confirmada'] == $consultaEstadoDetalle[0]['cantidad_solicitada']) {
                            $dataActualizarEstado['estado'] = 'Completado';
                            $dataActualizarEstado['usuario_modifica'] =  $data['usuario_modifica'];
                            $dataActualizarEstado['fecha_modifica']   =  $data['fecha_modifica'];
                            $actualizarDetalles = DetalleAccion::where('id_detalle', $items[$i]['id_detalle'])->update($dataActualizarEstado);
                        }

                        $dataAccionActualizar = new Acciones();
                        $dataAccion['usuario_modifica'] = $data['usuario_modifica'];
                        $dataAccion['fecha_modifica'] =  $data['fecha_modifica'];
                        $dataAccion['cantidad_solicitada'] = $this->sumarCantidadSolicitada($id_accion);
                        $dataAccion['cantidad_confirmada'] = $this->sumarCantidadConfirmada($id_accion);
                        $dataAccion['cantidad_pendiente']  = $this->sumarCantidadPendiente($id_accion);
                        $dataAccionActualizar = Acciones::where('id_accion', $id_accion)->update($dataAccion);

                        $actualizarEstadoAccion = Acciones::
                        select('id_accion','cantidad_confirmada','cantidad_solicitada')
                        ->where('id_accion', $id_accion)
                        ->where('estado', 'Pendiente')
                        ->get();
                        if (count($actualizarEstadoAccion) > 0) {
                            $actualizar = new Acciones();
                            if ($actualizarEstadoAccion[0]['cantidad_solicitada'] == $actualizarEstadoAccion[0]['cantidad_confirmada']) {
                               $dataEstado['estado'] = 'Completado';
                               $dataEstado['fecha_confirmacion'] = $data['fecha_modifica'];
                               $dataEstado['usuario_modifica'] = $data['usuario_modifica'];
                               $dataEstado['fecha_modifica']   =  $data['fecha_modifica'];
                               $actualizar = Acciones::where('id_accion', $id_accion)->update($dataEstado);
                            }
                        }
                        
                    }
                }

                DB::commit();
                return response()->json([
                    "ok" =>true,
                    "data" =>$acciones,
                    "confirmado" =>'Se confirmo satisfactoriamente'
                ]);
               
            } else {
                return 'No se puede confirmar esta solicitud';
            }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                "ok" =>false,
                "data"=>$th->getMessage(),
                "errorConfirmado" =>'Hubo un error consulte con el Administrador del sistema'
            ]);
        }
    }


}
