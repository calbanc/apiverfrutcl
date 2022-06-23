<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subitem extends Model
{
    protected $connection = 'sqlsrv3';
    protected $table='SUBITEM';
    protected $dateFormat = 'd/m/y h:i:s';

    
    use HasFactory;
}
