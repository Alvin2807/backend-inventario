<?php

namespace App\Http\Controllers\Api;

use App\Models\Despacho;
use App\Http\Controllers\Controller;
use App\Models\Jefes;
use App\Models\VistaDespachoAlternos;
use App\Models\VistaDespachoInterno;
use Illuminate\Http\Request;

class DespachosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Mostrar despachos internos
        $despachos = VistaDespachoInterno::all();
        return response()->json([
            "ok" =>true,
            "data" =>$despachos
        ]);

       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function MostrarDespachosAlternos()
    {
        //Muestra los despachos alternos
        $despachos = VistaDespachoAlternos::all();
        return response()->json([
            "ok"=>true,
            "data"=>$despachos
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
    public function show(Despacho $despacho)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Despacho $despacho)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Despacho $despacho)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Despacho $despacho)
    {
        //
    }

    public function  mostrarDespachoJefe($id_despacho){
        //Mostrar jefes de despacho
        $despacho = Despacho::
        select('id_despacho','apellido','nombre','cargo','cod_empleado')
        ->where('estado','Activo')
        ->where('id_despacho', $id_despacho)
        ->get();
        return response()->json([
            "ok" =>true,
            "data"=>$despacho
        ]);
    }
}
