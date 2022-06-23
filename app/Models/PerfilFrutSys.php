<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilFrutSys extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv2';
    protected $table='Perfil';
    protected $dateFormat = 'd/m/y h:i:s';
    
}
