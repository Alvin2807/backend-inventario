<?php

namespace App\Http\Controllers\Api;

use App\Models\Producto;
use App\Http\Controllers\Controller;
use App\Models\vista_productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Productos\StoreRequest;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Mostrar productos
        $producto = vista_productos::all();
        return response()->json([
            "ok" =>true,
            "data" =>$producto
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
        //Registrar producto
        try {
           DB::beginTransaction();
           $codigo_producto = strtoupper($request->input('codigo_producto'));
           $consultar = Producto::
           select('id_producto','codigo_producto')
           ->where('codigo_producto', $codigo_producto)
           ->get();
           if (count($consultar) > 0) {
            return response()->json([
                'ok' =>true,
                "existe" =>'Ya existe el código '.$codigo_producto
            ]);
           } else {
            $producto = new Producto();
            $producto->fk_categoria = $request->input('fk_categoria');
            $producto->fk_marca = $request->input('fk_marca');
            $producto->fk_modelo = $request->input('fk_modelo');
            $producto->fk_nomenclatura = $request->input('fk_nomenclatura');
            $producto->fk_color = $request->input('fk_color');
            $producto->fk_unidad_medida = $request->input('fk_unidad_medida');
            $producto->codigo_producto = $codigo_producto;
            $producto->stock = 0;
            $producto->estado = 'Agotado';
            $producto->usuario_crea = strtoupper($request->input('usuario'));
            $producto->save();

            DB::commit();
            return response()->json([
                "ok" =>true,
                "data" =>$producto,
                "exitoso" =>'Se guardo satisfactoriamente'
            ]);
           }
        } catch (\Exception $th) {
           DB::rollBack();
           return response()->json([
            "ok" =>false,
            "data" =>$th->getMessage(),
            "error" =>'Hubo un error consulte con el Administrador del Sistema'
           ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
