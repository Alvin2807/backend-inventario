<?php

namespace App\Http\Controllers\Api;

use App\Models\Nomenclatura;
use App\Http\Controllers\Controller;
use App\Models\vista_nomenclaturas;
use Illuminate\Http\Request;

class NomenclaturasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Mostrar nomenclaturas
        $nomenclatura = vista_nomenclaturas::all();
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
    public function store(Request $request)
    {
       
       

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
    public function edit(Nomenclatura $nomenclatura)
    {
        //
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
