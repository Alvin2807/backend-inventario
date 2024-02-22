<?php

namespace App\Utils;
use Carbon\Carbon;

class Utilidades  
{
    public static function formatoFecha($fecha)
    {
        return Carbon::createFromFormat('Y-m-d', $fecha)->toDateString();
    }
    
}