<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoLineaPk extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv3';
    protected $table='TIPOLINEAPK';
    protected $dateFormat = 'd/m/y h:i:s';
  

     //relacion de uno a muchos
  
}
