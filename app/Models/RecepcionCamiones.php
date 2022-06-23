<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecepcionCamiones extends Model
{
    protected $connection = 'sqlsrv3';
    protected $table='ANDROID_RECEPCION_CAMIONES';
    protected $dateFormat = 'd/m/y h:i:s';
    public $timestamps = false;
}
  