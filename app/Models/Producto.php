<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    public    $table        = 'ins_productos';
    protected $primarykey   = 'id_producto';
    protected $fillable     = ['id_producto','fk_marca','fk_modelo','fk_nomenclatura','fk_color','fk_unidad_medida','codigo_producto','estado','stock'];
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
        'stock' => 'integer'
    ];
}
