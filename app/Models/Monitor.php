<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    protected $connection = 'sqlsrv';
    protected $table='MONITOR';

    protected $dateFormat = 'd/m/y h:i:s';

    protected $fillable = [
        'IDEMPRESA',
        'DESCRIPCION',
        'CANTIDAD',
        'SW_OPERATIVO'
    ];

    use HasFactory;
}
