<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    public    $table        = 'ins_productos';
    protected $primarykey   = 'id_producto';
    protected $fillable     = ['id_producto','fk_marca','fk_modelo','fk_nomenclatura','fk_color','fk_unidad_medida','codigo_producto','estado','stock','cantidad_solicitada',
    'fecha_ultima_entrada','fecha_ultima_salida'];
    public    $incrementing = true;
    public    $timestamps   = false;

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
        'cantidad_solicitada'=>'integer',
        'fecha_ultima_entrada' =>'datetime:Y-m-d',
        'fecha_ultima_salida' =>'datetime:Y-m-d'
    ];
}
