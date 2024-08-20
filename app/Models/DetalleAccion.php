<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleAccion extends Model
{
    use HasFactory;

    public    $table        = 'ins_detalle_acciones';
    protected $primarykey   = 'id_detalle';
    protected $fillable     = ['fk_insumo','fk_tipo_accion','cantidad_solicitada','cantidad_confirmada','cantidad_pendiente','estado','fk_accion','registrado_por','observacion'];
    public    $incrementing = true;
    public    $timestamps   = false;

    protected $casts = 
    [
        'id_detalle'          =>'integer',
        'fk_tipo_accion'      =>'integer',
        'cantidad_solicitada' =>'integer',
        'cantidad_confirmada' =>'integer',
        'cantidad_pendiente'  =>'integer',
        'fk_accion'           =>'integer',
        'fk_insumo'           =>'integer'
    ];
}
