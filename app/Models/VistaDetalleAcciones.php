<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistaDetalleAcciones extends Model
{
    use HasFactory;

    public    $table    = "vista_detalle_acciones";
    protected $fillable = ['fk_insumo','fk_tipo_accion','cantidad_solicitada','cantidad_confirmada','cantidad_pendiente','estado','fk_accion','registrado_por','observacion'];

    protected $casts    = 
    [
        'fk_accion'     => 'integer'
    ];
}
