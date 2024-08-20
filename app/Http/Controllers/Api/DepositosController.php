<?php

namespace App\Http\Controllers\Api;

use App\Models\Deposito;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepositosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Mostrar despositos
        $deposito = Deposito::
        select('id_deposito','fk_piso','fk_despacho','deposito')
        ->orderBy('id_deposito','asc')
        ->get();
        return response()->json([
            "ok"=>true,
            "data"=>$deposito
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Deposito $deposito)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deposito $deposito)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deposito $deposito)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deposito $deposito)
    {
        //
    }
}
