<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistaModeloMarca extends Model
{
    use HasFactory;

    public    $table = 'vista_modelos_marca';
    protected $fillable = ['id_modelo','nombre_modelo'];

    protected $casts = [
        'id_modelo' =>'integer',
        'nombre_modelo' =>'string'
    ];
}
