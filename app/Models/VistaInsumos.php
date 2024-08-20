<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistaInsumos extends Model
{
    use HasFactory;

    public    $table    = 'vista_insumos';
    protected $fillable = ['id_insumo','codigo','categoria','marca','modelo','color','nomenclatura',
    'fk_nomenclatura','fk_marca','fk_modelo','fk_categoria','fk_color','estado','stock','cantidad_pedida'];

    protected $casts = 
    [
        'id_insumo' =>'integer',
        'codigo'    =>'string',
        'categoria' =>'string',
        'marca'     =>'string',
        'modelo'    =>'string',
        'color'     =>'string',
        'nomenclatura' =>'string',
        'fk_nomenclatura' =>'integer',
        'fk_marca'  =>'integer',
        'fk_modelo' =>'integer',
        'fk_categoria' =>'integer',
        'fk_color' =>'integer',
        'estado' =>'string',
        'stock'  =>'integer',
        'cantidad_pedida' =>'integer'
    ];

}
