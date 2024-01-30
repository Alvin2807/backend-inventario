<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vista_nomenclaturas extends Model
{
    use HasFactory;
    public    $table = 'vista_nomenclaturas';
    protected $fillable = ['id_nomenclatura','fk_modelo','nombre_modelo','nomenclatura'];

    protected $casts = [
        'id_nomenclatura' =>'integer',
        'fk_modelo' =>'integer',
        'nomenclatura' =>'string',
        'nombre_modelo' =>'string'
    ];
}
