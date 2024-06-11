<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistaMarcaModelo extends Model
{
    use HasFactory;
    public    $table    = "vista_marcas_modelos";
    protected $fillable = ['id_marca','id_modelo','marca','modelo'];

    protected $casts    = 
    [
        'id_modelo' =>'integer',
        'id_marca'  =>'integer',
        'marca'     =>'string',
        'modelo'    =>'string'
    ];
}
