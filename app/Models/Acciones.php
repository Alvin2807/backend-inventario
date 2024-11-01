<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acciones extends Model
{
    use HasFactory;

    public    $table        = 'ins_acciones';
    protected $primarykey   = 'id_accion';
    protected $fillable     = ['id_accion','fk_despacho','no_control','no_salida','fecha_entrada','fecha_salida','fecha_confirmacion','no_incidencia','estado','fk_tipo_accion',
    'cantidad_solicitada','cantidad_confirmada','cantidad_pendiente'];
    public    $incrementing = true;
    public    $timestamps   = false;

    protected $casts  =
    [
        'id_accion'             =>'integer',
        'fk_despacho'           =>'integer',
        'no_control'            =>'string',
        'no_salida'             =>'string',
        'fecha_entrada'         =>'datetime:Y-m-d',
        'fecha_salida'          =>'datetime:Y-m-d',
        'fecha_confirmacion'    =>'datetime:Y-m-d',
        'no_incidencia'         =>'integer',
        'estado'                =>'string',
        'fk_tipo_accion'        =>'integer',
        'cantidad_solicitada'   =>'integer',
        'cantidad_confirmada'   =>'integer',
        'cantidad_pendiente'    =>'integer'
    ];
}
