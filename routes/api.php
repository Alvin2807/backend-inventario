<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriasController;
use App\Http\Controllers\Api\MarcasController;
use App\Http\Controllers\Api\ModelosController;
use App\Http\Controllers\Api\NomenclaturasController;
use App\Http\Controllers\Api\ColoresController;
use App\Http\Controllers\Api\ProductosController;
use App\Http\Controllers\Api\AccionesController;
use App\Http\Controllers\Api\LoginController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::apiResource('categoria', CategoriasController::class);
Route::apiResource('colores', ColoresController::class);
Route::apiResource('nomenclaturas', NomenclaturasController::class);
Route::apiResource('modelos', ModelosController::class);
Route::apiResource('marcas',MarcasController::class);
Route::apiResource('productos',ProductosController::class);
Route::apiResource('acciones', AccionesController::class);
Route::apiResource('login', LoginController::class);
Route::get('acciones_pendientes/{id_accion}', [AccionesController::class,'accionesPendientes']);
Route::get('buscar_producto_stock', [ProductosController::class,'buscarProductoUbicacion']);
Route::put('editar_solicitud', [AccionesController::class,'editarSolicitud']);
Route::put('editar_marca',[MarcasController::class,'editarMarca']);
Route::put('cancelar_producto', [AccionesController::class,'cancelarProductoSolicitud']);
Route::put('cancelar_accion',[AccionesController::class, 'CancelarSolicitud']);
Route::put('confirmacion_parcial', [AccionesController::class, 'confirmacionParcial']);
Route::put('confirmacion_global_entrada', [AccionesController::class,'confirmacionGlobalEntrada']);
Route::put('editar_categoria',[CategoriasController::class,'editarCategoria']);
Route::put('editar_nomenclatura',[NomenclaturasController::class,'editarNomenclatura']);
Route::put('editar_modelo',[ModelosController::class,'editarModelo']);
Route::post('registrar_salida', [AccionesController::class,'registrarAccionesSalida']);
Route::post('iniciar_seccion',[LoginController::class,'inciarSesion']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
