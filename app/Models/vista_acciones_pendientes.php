<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vista_acciones_pendientes extends Model
{
    use HasFactory;

    public    $table = 'vista_acciones_pendientes';
    protected $fillable = ['id_accion','no_nota','fecha_nota','titulo_nota','fk_tipo_accion','incidencia','fecha_salida',
    'fecha_confirmacion','fk_despacho_solicitante','fk_despacho_asignado','cantidad_solicitada','cantidad_confirmada','cantidad_pendiente',
    'cantidad_entregada','estado','observacion','tipo_accion','despacho_asignado','despacho_solicitante'];

    protected $casts =
    [
        'id_accion'            =>'integer',
        'no_nota'              =>'string',
        'fecha_nota'           =>'datetime:Y-m-d',
        'titulo_nota'          =>'string',
        'fk_tipo_accion'       =>'integer',
        'incidencia'           =>'integer',
        'fecha_salida'         =>'datetime:Y-m-d',
        'cantidad_confirmada'  =>'integer',
        'cantidad_pendiente'   =>'integer',
        'cantidad_entregada'   =>'integer',
        'estado'               =>'string',
        'observacion'          =>'string',
        'tipo_accion'          =>'integer',
        'despacho_asignado'    =>'string',
        'despacho_solicitante' =>'string',
    ];
}
