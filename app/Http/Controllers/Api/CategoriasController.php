<?php

namespace App\Http\Controllers\Api;

use App\Models\Categoria;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Categoria\StoreRequest;
use App\Http\Requests\Categoria\EditarRequest;
use Carbon\Carbon;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Mostrar las categorias
        $categoria = Categoria::
        select('id_categoria','categoria')
        ->orderby('categoria','asc')
        ->get();
        return response()->json([
            "ok" =>true,
            "data"=>$categoria
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
        //Registrar Categoria
        DB::beginTransaction();
        try {
           $categoria = strtoupper($request->input('categoria'));
           $consulta = Categoria::
           select('id_categoria','categoria')
           ->where('categoria',$categoria)
           ->get();
           if (count($consulta) > 0) {
            return response()->json([
                "ok" =>true,
                "existe" =>'Ya existe la categoría '.$categoria
            ]);
           } else {
            $categorias = new Categoria();
            $categorias->categoria = $categoria;
            $categorias->usuario_crea = strtoupper($request->input('usuario'));
            $categorias->save();
            DB::commit();
            return response()->json([
                "ok" =>true,
                "data" =>$categorias,
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
    public function editarCategoria(EditarRequest $request)
    {
        //Editar una categoría
        DB::beginTransaction();
        try {
            $categoria    = strtoupper($request->input('categoria'));
            $id_categoria = (int)$request->input('id_categoria');
            $usuario      = strtoupper($request->input('usuario'));
            $consulta     = Categoria::
            select('id_categoria','categoria')
            ->where('categoria', $categoria)
            ->where('id_categoria','<>', $id_categoria)
            ->get();
            if (count($consulta) > 0) {
                return response()->json([
                    "ok" =>true,
                    "existeCategoria" =>'Ya existe una categoría '.$categoria
                ]);
            } else {
                $categorias = new Categoria();
                $data['categoria'] = $categoria;
                $data['usuario_modifica'] = $usuario;
                $data['fecha_modifica']   = Carbon::now()->format('Y-m-d H:i:s');
                $categorias = Categoria::where('id_categoria', $id_categoria)->update($data);
                DB::commit();
                return response()->json([
                    "ok" =>true,
                    "data"=>$categorias,
                    "modificado"=>'Se guardo satisfactoriamente'
                ]);
            }

            
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                "ok" =>false,
                "data"=>$th->getMessage(),
                "errorModifica" =>'Hubo un error consulte con el Administrador del sistema'
            ]);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        //
    }
}
