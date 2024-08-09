<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistaAciones extends Model
{
    use HasFactory;

    public    $table      = 'vista_acciones';
    protected $fillable   = ['id_accion','fk_despacho','no_nota','titulo_nota',
    'fecha_nota','fecha_confirmacion','no_incidencia','observacion','estado',
    'fk_tipo_accion','registrado_por','despacho','cantidad_solicitada','cantidad_confirmada','cantidad_pendiente'];

    protected $casts  =
    [
        'id_accion'             =>'integer',
        'fk_despacho'           =>'integer',
        'no_nota'               =>'string',
        'titulo_nota'           =>'string',
        'fecha_nota'            =>'datetime:Y-m-d',
        'fecha_confirmacion'    =>'datetime:Y-m-d',
        'no_incidencia'         =>'integer',
        'observacion'           =>'string',
        'fk_tipo_accion'        =>'integer',
        'cantidad_solicitada'   =>'integer',
        'cantidad_confirmada'   =>'integer',
        'cantidad_pendiente'    =>'integer'
    ];
}
