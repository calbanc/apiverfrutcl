<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingParametros extends Model
{
    protected $connection = 'sqlsrv3';
    protected $table='PACKINGS_PARAMETROS';
    protected $dateFormat = 'd/m/y h:i:s';
}
