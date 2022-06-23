<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstacionMarcacion extends Model
{
    protected $connection = 'sqlsrv5';
    protected $table='Estacion_Marcacion_Prueba';

    protected $dateFormat = 'd/m/y h:i:s';


    use HasFactory;
}
