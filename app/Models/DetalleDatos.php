<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleDatos extends Model
{
    protected $connection = 'sqlsrv6';
    protected $table='DETALLE_DATOS';
    public $timestamps = false;
}
