<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desktop extends Model
{
    protected $connection = 'sqlsrv';
    protected $table='DESKTOP';
    protected $dateFormat = 'd/m/y h:i:s';

    protected $fillable = [
        'PROCESADOR',
        'RAM',
        'SSDD',
        'SW_OPERATIVO',
        'SW_ARRENDADO',
        'COD_EMP',
        'SERIE'
    ];
}
