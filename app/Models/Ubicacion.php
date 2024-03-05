<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;

    public    $table        = 'ins_ubicaciones';
    protected $primarykey   = 'id_ubicacion';
    protected $fillable     = ['id_ubicacion','fk_producto','fk_localizacion','stock'];
    public    $incrementing = true;
    public    $timestamps   = false;

    protected $casts = [
        'id_ubicacion' =>'integer',
        'fk_producto'  =>'integer',
        'fk_localizacion' =>'integer',
        'stock' =>'integer'
    ];
}
