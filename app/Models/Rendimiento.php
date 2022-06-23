<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rendimiento extends Model
{
    
    protected $connection = 'sqlsrv5';
    protected $table='Rendimiento_Planta';
    protected $dateFormat = 'd/m/y h:i:s';
     //relacion de uno a muchos
}