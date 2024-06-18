<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistaMarcaModelo extends Model
{
    use HasFactory;
    public    $table    = "vista_modelos_marca";
    protected $fillable = ['id_modelo','fk_marca','marca','modelo'];

    protected $casts    = 
    [
        'id_modelo' =>'integer',
        'fk_marca'  =>'integer',
        'marca'     =>'string',
        'modelo'    =>'string'
    ];
}
