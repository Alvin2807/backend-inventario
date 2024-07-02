<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;

    public    $table        = 'ins_insumos';
    protected $primarykey   = 'id_insumo';
    protected $fillable     = ['id_insumo','fk_nomenclatura','fk_marca','fk_modelo','fk_categoria','fk_color','codigo','estado','stock'];
    public    $incrementing = true;
    public    $timestamps   = false;
}
