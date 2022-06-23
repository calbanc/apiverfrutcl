<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporadaRemuneraciones extends Model
{
    protected $connection = 'sqlsrv4';
    protected $table='Temporada';
    protected $fillable = [
        'IdEmpresa',
        'IdTemporada',
        'Descripcion'
    ];
}
