<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistaInsumosDespachos extends Model
{
    use HasFactory;
    public    $table     = 'vista_insumos_despachos';
    protected $fillable  = ['id_insumo','codigo','categoria','marca','modelo','color', 'nomenclatura', 'referencia', 'stock', 'estado', 'fk_despacho','fk_nomenclatura'];

    protected $casts      = 
    [
        'id_insumo' => 'integer',
        'stock'     => 'integer',
        'fk_despacho' =>'integer',
        'fk_nomenclatura' =>'integer'
    ];
}
