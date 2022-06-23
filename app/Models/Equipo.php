<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\EmpresaRemuneraciones;

class Equipo extends Model
{
    protected $connection = 'sqlsrv';
    protected $table='EQUIPO';

    protected $dateFormat = 'd/m/y h:i:s';

    protected $fillable=[
        'IDEMPRESA',
        'IDZONA',
        'IDEMPRESATRABAJADOR',
        'IDTRABAJADOR',
        'VPE',
        'ID_NOTEBOOK',
        'IDTECLADO',
        'ID_MONITOR',
        'ID_MOUSE',
        'ID_DESKTOP',
        'ID_TELEFONO',
        'SW_NOTEBOOK',
        'SW_DESKTOP',
        'SW_TELEFONO',
        'OBSERVACIO',
        'DEVOLUCION',
        'ID_USUARIO',
        'NOMBREUSUARIO',
        'NTELEFONO',
        'NOM_ZON',
        'NOM_EMP',
        'NOM_EMP_TRAB',
        'NOM_TRAB'
    ];





    use HasFactory;
}
