<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;

    public    $table      = 'ins_ubicaciones';
    protected $primarykey = 'id_ubicacion';
    protected $fillable   = ['id_ubicacion','fk_insumo','fk_deposito','stock','usuario_crea','usuario_modifica','fecha_modifica'];
    public    $incrementing = true;
    public    $timestamps   = false;
    protected $casts      = 
    [
        'id_ubicacion'    =>'integer',
        'fk_insumo'       =>'integer',
        'stock'           =>'integer',
        'fk_deposito'     =>'integer'
    ];
}
