<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleAccion extends Model
{
    use HasFactory;
    
    public    $table = 'ins_detalle_acciones';
    protected $primarykey = 'id_detalle';
    protected $fillable = ['id_detalle','fk_accion','fk_producto','cantidad_solitada','cantidad_confirmada',
    'cantidad_pendiente','cantidad_entregada','estado','observacion'];
    public $incrementing = true;
    public $timestamps = false;

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
        'observacion' =>'string'
    ];
}
