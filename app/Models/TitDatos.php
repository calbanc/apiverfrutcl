<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitDatos extends Model
{
    protected $connection = 'sqlsrv6';
    protected $table='TIT_DATOS';
    public $timestamps = false;
}
