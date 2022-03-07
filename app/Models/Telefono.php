<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telefono extends Model
{
    protected $connection = 'sqlsrv';
    protected $table='TELEFONO';
    protected $dateFormat = 'd/m/y h:i:s';

    protected $fillable=[
        'COD_EMP',
        'IMEI',
        'MARCA',
        'MODELO',
        'EMAIL',
        'SW_OPERATIVO'
    ];
    use HasFactory;
}
