<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accion extends Model
{
    use HasFactory;

    public $table = 'ins_acciones';
    protected $primarykey = 'id_accion';
    protected $fillable = ['id_accion','no_nota','fecha_nota','titulo_nota','fk_tipo_accion','incidencia','fecha_salida',
    'fecha_confirmacion','fk_despacho_solicitante','fk_despacho_asignado','cantidad_solicitada','cantidad_confirmada','cantidad_pendiente',
    'cantidad_entregada','estado','observacion'];
    public $incrementing = true;
    public $timestamps = false;

    protected $casts = [
        'id_accion' =>'integer',
        'no_nota'   =>'integer',
        'fecha_nota' =>'datetime:Y-m-d',
        'titulo_nota' =>'string',
        'fk_tipo_accion' =>'integer',
        'incidencia' =>'integer',
        'fecha_salida' =>'datetime:Y-m-d',
        'fecha_confirmacion' =>'datetime:Y-m-d',
        'fk_despacho_asignado' =>'integer',
        'cantidad_solicitaba' =>'integer',
        'cantidad_confirmada' =>'integer',
        'cantidad_entregada' =>'integer',
        'estado' => 'string',
        'observacion' =>'string'
    ];
}
