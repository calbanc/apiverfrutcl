<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rendiciones extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv3';
    protected $table='APP_TransRENDICIONESCHOFERES';
    protected $dateFormat = 'd/m/y h:i:s';
    public $timestamps = false;
     //relacion de uno a muchos
}

