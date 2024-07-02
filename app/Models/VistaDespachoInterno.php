<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistaDespachoInterno extends Model
{
    use HasFactory;

    public    $table = "vista_despachos_internos";
    protected $fillable = ['id_despacho','despacho','fk_provincia','estado'];

    protected $casts = 
    [
        'id_despacho'  =>'integer',
        'despacho'     =>'string',
        'fk_provincia' =>'integer',
        'estado'       =>'string'
    ];
}
