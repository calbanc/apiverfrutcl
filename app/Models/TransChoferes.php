<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransChoferes extends Model
{
    protected $connection = 'sqlsrv3';
    protected $table='TransManGASTOS_OT';
    protected $dateFormat = 'd/m/y h:i:s';


 


}
