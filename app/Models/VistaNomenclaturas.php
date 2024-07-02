<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistaNomenclaturas extends Model
{
    use HasFactory;

    public     $table      = 'vista_nomenclaturas';
    protected  $fillable   = ['id_nomenclatura','nomenclatura','despacho','producto_agregado','fk_despacho'];

    protected  $casts =
    [
        'id_nomenclatura'   =>'integer',
        'nomenclatura'      =>'string',
        'despacho'          =>'string',
        'producto_agregado' =>'string',
        'fk_despacho'       =>'integer'
    ];
}
