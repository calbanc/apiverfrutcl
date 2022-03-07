<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    protected $connection = 'sqlsrv';
    protected $table='APP';
    protected $dateFormat = 'd/m/y h:i:s';


     //relacion de uno a muchos
    public function perfil(){
        return $this->hasMany('App\Models\Perfil');
    }


}
