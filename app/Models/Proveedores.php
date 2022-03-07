<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    protected $connection = 'sqlsrv3';
    protected $table='Proveedores';
    protected $dateFormat = 'd/m/y h:i:s';
}
