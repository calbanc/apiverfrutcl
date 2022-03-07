<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaRemuneraciones extends Model
{
    protected $connection = 'sqlsrv4';
    protected $table='Empresa';
}
