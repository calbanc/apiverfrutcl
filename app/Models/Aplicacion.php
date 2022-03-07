<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aplicacion extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv2';
    protected $table='Aplicacion';
    protected $dateFormat = 'd/m/y h:i:s';


     //relacion de uno a muchos
  
}
