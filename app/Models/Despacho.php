<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despacho extends Model
{
    use HasFactory;

    public    $table ="ins_despachos";
    protected $primarykey = "id_despacho";
    protected $fillable = ['id_despacho','despacho','fk_provincia','estado','cargo','profesion','siglas','appellido','nombre'];
    
    protected $casts = 
    [
        'id_despacho'   =>'integer',
        'despacho'      =>'string',
        'fk_provincia'  =>'integer',
        'estado'        =>'string'
    ];

}
