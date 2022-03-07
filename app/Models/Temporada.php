<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temporada extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv3';
    protected $table='TEMPORADAS';
    protected $dateFormat = 'd/m/y h:i:s';
    protected $fillable = [
        'COD_EMP',
        'COD_TEM',
        'DESCRIPCION'
    ];


     //relacion de uno a muchos
  
}
