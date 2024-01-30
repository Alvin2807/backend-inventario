<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    use HasFactory;
    public    $table        = 'ins_unidad_medidas';
    protected $primarykey   = 'id_unidad_medida';
    protected $fillable     = ['id_unidad_medida','unidad_medida'];
    public    $incrementing = true;
    public    $timestamps   = false;

    protected $casts = 
    [
        'id_unidad_medida' => 'integer',
        'unidad_medida'    => 'string'
    ];

}
