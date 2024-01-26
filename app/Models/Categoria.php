<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    public    $table        = 'ins_categorias';
    protected $primarykey   = 'id_categoria';
    protected $fillable     = ['id_categoria','categoria'];
    public    $incrementing = true;
    public    $timestamps   = false;

    protected $casts = [ 
        'id_categoria' => 'integer',
        'categoria' => 'string'
    ];
}
