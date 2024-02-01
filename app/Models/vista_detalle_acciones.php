<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vista_detalle_acciones extends Model
{
    use HasFactory;

    public    $table = 'vista_detalle_acciones';
    protected $fillable = ['id_detalle','fk_accion','fk_producto','cantidad_solitada','cantidad_confirmada',
    'cantidad_pendiente','cantidad_entregada','estado','observacion','categoria','nombre_marca','nombre_modelo','nomenclatura',
    'unidad_medida','color','usuario_crea','usuario_modifica'];
   

    protected $casts  = 
    [ 
        'id_detalle' =>'integer',
        'fk_accion'  =>'integer',
        'fk_producto' =>'integer',
        'cantidad_solicitada'=>'integer',
        'cantidad_confirmada' =>'integer',
        'cantidad_pendiente' =>'integer',
        'cantidad_entregada'=>'integer',
        'estado' => 'string',
        'observacion' =>'string',
        'usuario_crea' =>'string',
        'usuario_modifica' =>'string'
    ];
}
