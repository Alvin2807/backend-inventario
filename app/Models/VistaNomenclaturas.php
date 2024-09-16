<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistaNomenclaturas extends Model
{
    use HasFactory;

    public     $table      = 'vista_nomenclaturas';
    protected  $fillable   = ['id_nomenclatura','nomenclatura','despacho','fk_despacho'];

    protected  $casts =
    [
        'id_nomenclatura'   =>'integer',
        'nomenclatura'      =>'string',
        'despacho'          =>'string',
        'fk_despacho'       =>'integer'
    ];
}
