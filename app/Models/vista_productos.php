<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vista_productos extends Model
{
    use HasFactory;
    public    $table        = 'vista_productos';
    protected $fillable     = ['id_producto','fk_marca','fk_modelo','fk_nomenclatura','fk_color','fk_unidad_medida',
    'codigo_producto','estado','stock','nombre_marca','nombre_modelo','categoria','color','unidad_medida','nomenclatura','fecha_ultima_entrada','fecha_ultima_salida'];

    protected $casts       = 
    [
        'id_producto'  => 'integer',
        'fk_marca'     => 'integer',
        'fk_modelo'    => 'integer',
        'fk_nomenclatura' => 'integer',
        'fk_color' => 'integer',
        'fk_unidad_medida' => 'integer',
        'codigo_producto' => 'string',
        'estado' => 'string',
        'stock' => 'integer',
        'categoria' =>'string',
        'nombre_marca' =>'string',
        'nombre_modelo' =>'string',
        'color' => 'string',
        'unidad_medida' =>'string',
        'fecha_ultima_entrada' =>'datetime:Y-m-d',
        'fecha_ultima_salida' =>'datetime:Y-m-d'
    ];
}
