<?php

namespace App\Http\Controllers\Api;

use App\Models\Color;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Colores\StoreRequest;
class ColoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Mostrar colores
        $color = Color::
        select('id_color', 'color')
        ->get();
        return response()->json([
            'ok' =>true,
            "data" =>$color
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
        //Registrar Colores
        try {
            DB::beginTransaction();
            $color = strtoupper($request->input('color'));
            $colores = Color::
            select('id_color','color')
            ->where('color', $color)
            ->get();
            if (count($colores) > 0) {
               return response()->json([
                "ok" =>true,
                "existe" =>'Ya existe el color '.$color
               ]);
            } else {
                $registrarColor = new Color();
                $registrarColor->color = $color;
                $registrarColor->usuario_crea = strtoupper($request->input('usuario'));
                $registrarColor->save();

                DB::commit();
                return response()->json([
                    "ok" =>true,
                    "data" =>$registrarColor,
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
     * Display the specified resource.
     */
    public function show(Color $color)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Color $color)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Color $color)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
    {
        //
    }
}
