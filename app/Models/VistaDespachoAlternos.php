<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistaDespachoAlternos extends Model
{
    use HasFactory;

    public    $table    = "vista_despachos_alternos";
    protected $fillable = ['id_despacho','despacho','estado','fk_provicia','provincia'];
    
}
