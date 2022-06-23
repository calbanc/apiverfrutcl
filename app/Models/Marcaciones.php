<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marcaciones extends Model
{
    protected $connection = 'sqlsrv';
    protected $table='Marcaciones_Android';

    protected $dateFormat = 'd/m/y h:i:s';

  
    use HasFactory;
}
