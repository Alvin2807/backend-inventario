<?php

namespace App\Http\Controllers\Api;

use App\Models\Accion;
use App\Http\Controllers\Controller;
use App\Models\vista_acciones_pendientes;
use App\Models\vista_detalle_acciones;
use Illuminate\Http\Request;

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
        $acciones = vista_acciones_pendientes::
        select('id_accion','no_nota','fecha_nota','titulo_nota','fk_tipo_accion','incidencia','fecha_salida','cantidad_confirmada',
        'cantidad_pendiente','cantidad_entregada','estado','observacion','tipo_accion','despacho_asignado','despacho_solicitante',
        'usuario_crea','usuario_modifica')
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Accion $accion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Accion $accion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Accion $accion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accion $accion)
    {
        //
    }
}
