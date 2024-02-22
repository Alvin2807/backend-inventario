<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoAccion extends Model
{
    use HasFactory;

    public    $table      = 'ins_tipo_acciones';
    protected $primarykey = 'id_tipo_accion';
    protected $fillable   = ['id_tipo_accion','tipo_accion'];

    protected $casts = 
    [
        'id_tipo_accion' =>'integer',
        'tipo_accion'    =>'string'
    ];

}
