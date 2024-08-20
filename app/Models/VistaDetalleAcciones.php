<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistaDetalleAcciones extends Model
{
    use HasFactory;

    public    $table    = "vista_detalle_acciones";
    protected $fillable = ['fk_insumo','fk_tipo_accion','cantidad_solicitada','cantidad_confirmada','cantidad_pendiente','estado',
    'fk_accion','registrado_por','observacion','no_item', 'id_detalle'];

    protected $casts    = 
    [
        'fk_accion'     => 'integer',
        'no_item'       => 'integer',
        'cantidad_solicitada' => 'integer',
        'cantidad_confirmada' => 'integer',
        'cantidad_pendiente'  => 'integer',
        'fk_tipo_accion' => 'integer',
        'fk_insumo' => 'integer',
        'id_detalle' =>'integer'
    ];
}
