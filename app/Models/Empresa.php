<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{


    protected $connection = 'sqlsrv3';
    protected $table='EMPRESAS';
    protected $fillable = [
        'COD_EMP',
        'NOM_EMP'
    ];
   
    public function notebook(){
        return $this->hashMany('App\Models\Notebook');
    }
}
