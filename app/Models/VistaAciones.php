<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistaAciones extends Model
{
    use HasFactory;

    public    $table      = 'vista_acciones';
    protected $fillable   = ['id_accion','fk_despacho','no_control','no_salida',
    'fecha_entrada','fecha_confirmacion','no_incidencia','estado','fecha_salida',
    'fk_tipo_accion','despacho','cantidad_solicitada','cantidad_confirmada','cantidad_pendiente'];

    protected $casts  =
    [
        'id_accion'             =>'integer',
        'fk_despacho'           =>'integer',
        'fecha_entrada'         =>'datetime:Y-m-d',
        'fecha_salida'          =>'datetime:Y-m-d',
        'fecha_confirmacion'    =>'datetime:Y-m-d',
        'no_incidencia'         =>'integer',
        'fk_tipo_accion'        =>'integer',
        'cantidad_solicitada'   =>'integer',
        'cantidad_confirmada'   =>'integer',
        'cantidad_pendiente'    =>'integer'
    ];
}
