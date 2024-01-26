<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    public    $table        = 'ins_marcas';
    protected $primarykey   = 'id_marca';
    protected $fillable     = ['id_marca','nombre_marca'];
    public    $incrementing = true;
    public    $timestamps   = false;

    protected $casts = [
        'id_marca' => 'integer',
        'nombre_marca' =>'string'
    ];
}
