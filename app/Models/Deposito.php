<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposito extends Model
{
    use HasFactory;
    public    $table         = "ins_depositos";
    protected $primarykey    = "id_deposito";
    protected $fillable      = ['id_deposito','fk_piso','fk_despacho','deposito'];

    protected $casts     = 
    [
        'id_deposito'    =>'integer',
        'fk_piso'        =>'integer',
        'fk_despacho'    =>'integer'
    ];

    
}
