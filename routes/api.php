<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriasController;
use App\Http\Controllers\Api\MarcasController;
use App\Http\Controllers\Api\ModelosController;
use App\Http\Controllers\Api\NomenclaturasController;
use App\Http\Controllers\Api\ColoresController;
use App\Http\Controllers\Api\AccionesController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\DespachosController;
use App\Http\Controllers\Api\InsumosController;
use App\Http\Controllers\Api\DepositosController;
use App\Http\Controllers\Api\UbicacionesController;

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
Route::apiResource('acciones', AccionesController::class);
Route::apiResource('login', LoginController::class);
Route::apiResource('despachos', DespachosController::class);
Route::apiResource('insumos',InsumosController::class);
Route::apiResource('depositos', DepositosController::class);
Route::apiResource('ubicacion',UbicacionesController::class);
Route::get('traer_ultimo_registro_accion', [AccionesController::class,'traerUltimoRegistro']);
Route::get('despachos_alternos',[DespachosController::class,'MostrarDespachosAlternos']);
Route::get('total_notas', [AccionesController::class,'mostrarContadorNota']);
Route::get('acciones_pendientes/{id_accion}', [AccionesController::class,'accionesPendientes']);
Route::get('mostrar_jefe_despacho/{id_despacho}',[DespachosController::class,'mostrarDespachoJefe']);
Route::get('mostrar_modelos_por_marcas/{fk_marca}', [ModelosController::class,'selecionarMarcaModelo']);
Route::put('editar_solicitud', [AccionesController::class,'editarSolicitud']);
Route::put('confirmar_solicitud',[AccionesController::class,'cofirmarSolicitud']);
Route::put('cancelar_insumo_detalle', [AccionesController::class,'cancelarInsumo']);
Route::put('cancelar_accion',[AccionesController::class,'cancelarAccion']);
Route::put('editar_accion',[AccionesController::class,'editarAccion']);
Route::put('editar_marca',[MarcasController::class,'editarMarca']);
Route::put('confirmacion_parcial', [AccionesController::class, 'confirmacionParcial']);
Route::put('confirmacion_global_entrada', [AccionesController::class,'confirmacionGlobalEntrada']);
Route::put('editar_categoria',[CategoriasController::class,'editarCategoria']);
Route::put('editar_nomenclatura',[NomenclaturasController::class,'editarNomenclatura']);
Route::put('editar_modelo',[ModelosController::class,'editarModelo']);
Route::put('editar_insumo', [InsumosController::class,'editarInsumo']);
Route::post('registrar_salida', [AccionesController::class,'registrarAccionesSalida']);
Route::post('mostrar_nota_exite', [AccionesController::class,'mostrarNotaExiste']);
Route::post('iniciar_seccion',[LoginController::class,'inciarSesion']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
