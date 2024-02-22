<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriasController;
use App\Http\Controllers\Api\MarcasController;
use App\Http\Controllers\Api\ModelosController;
use App\Http\Controllers\Api\NomenclaturasController;
use App\Http\Controllers\Api\ColoresController;
use App\Http\Controllers\Api\UnidadMedidasController;
use App\Http\Controllers\Api\ProductosController;
use App\Http\Controllers\Api\AccionesController;
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
Route::apiResource('unidades_de_medidas', UnidadMedidasController::class);
Route::apiResource('productos',ProductosController::class);
Route::apiResource('acciones', AccionesController::class);
Route::get('acciones_pendientes/{id_accion}', [AccionesController::class,'accionesPendientes']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
