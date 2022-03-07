<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mouse extends Model
{
    protected $connection = 'sqlsrv';
    protected $table='MOUSE';
    protected $dateFormat = 'd/m/y h:i:s';

    protected $fillabe=[
        'IDEMPRESA',
        'DESCRIPCION',
        'CANTIDAD',
        'SW_OPERATIVO'
    ];
    use HasFactory;
}
